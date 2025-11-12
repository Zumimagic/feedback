FROM php:8.2-apache

RUN docker-php-ext-install mysqli

RUN a2enmod rewrite && \
    printf '<Directory /var/www/html/>\n\tAllowOverride All\n</Directory>\n' > /etc/apache2/conf-available/allow-override.conf && \
    a2enconf allow-override

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html
