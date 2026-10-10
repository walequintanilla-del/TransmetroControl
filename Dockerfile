
FROM php:8.2-apache

# Instalar la extension para conectarse a MySQL
RUN docker-php-ext-install mysqli \
    && docker-php-ext-enable mysqli \
    && php -m | grep -i '^mysqli$'

# Copiar los archivos del proyecto
COPY . /var/www/html/

# Asignar permisos
RUN chown -R www-data:www-data /var/www/html/

EXPOSE 80

CMD ["apache2-foreground"]
