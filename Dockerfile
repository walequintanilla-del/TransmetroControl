FROM php:8.2-apache

# INSTALAR Y ENCENDER EL MOTOR MYSQLI PARA LA NUBE
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# MOVER LOS SCRIPTS PHP AL DIRECTORIO DE RED
COPY . /var/www/html/

# ASIGNAR LOS PERMISOS UNIVERSALES DE LECTURA
RUN chown -R www-data:www-data /var/www/html/

EXPOSE 80
