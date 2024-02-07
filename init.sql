-- Crear la base de datos "worldcup"
CREATE DATABASE worldcup;

-- Conectar a la base de datos "worldcup"
\c worldcup;

-- Crear el usuario "worldcup" y otorgarle privilegios
CREATE USER worldcup WITH PASSWORD 'worldcup';
GRANT ALL PRIVILEGES ON DATABASE worldcup TO worldcup;
