# Usa la imagen oficial de PHP 8.2
FROM php:8.2.0-apache

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Mod Rewrite
RUN a2enmod rewrite

# Instala dependencias necesarias
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpq-dev \
    unzip \
    zip \
    ncat \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean

# Copia todos los archivos de tu proyecto al contenedor
COPY . .

# Copia el archivo .env.example a .env y configura la conexión a PostgreSQL
COPY .env.example .env
RUN sed -i 's/DB_CONNECTION=mysql/DB_CONNECTION=pgsql/g' .env \
    && sed -i 's/DB_HOST=127.0.0.1/DB_HOST=postgres/g' .env \
    && sed -i 's/DB_PORT=3306/DB_PORT=5432/g' .env \
    && sed -i 's/DB_DATABASE=your_database_name/DB_DATABASE=worldcup/g' .env \
    && sed -i 's/DB_USERNAME=your_username/DB_USERNAME=worldcup/g' .env \
    && sed -i 's/DB_PASSWORD=your_password/DB_PASSWORD=worldcup/g' .env

# Instala las dependencias de composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

# Espera a que PostgreSQL esté disponible
COPY wait-for-it.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/wait-for-it.sh
RUN php artisan key:generate

# Ejecuta las migraciones y seeders una vez que PostgreSQL esté disponible
CMD ["bash", "-c", "php artisan migrate --seed && tail -f /dev/null"]
