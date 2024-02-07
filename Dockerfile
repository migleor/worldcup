# Usa la imagen oficial de PHP 8.2
FROM php:8.2-fpm

# Instala dependencias necesarias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia todos los archivos de tu proyecto al contenedor
COPY . .

# Instala las dependencias de composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

# Ejecuta las migraciones y seeders
RUN php artisan key:generate
RUN php artisan migrate --seed

# Exponer el puerto 9000 para el servidor PHP-FPM
EXPOSE 9000

# Comando por defecto para ejecutar el servidor PHP-FPM
CMD ["php-fpm"]
