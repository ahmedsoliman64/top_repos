FROM php:7.3-apache

COPY . /var/www/html/
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf
RUN chown -R www-data:www-data /var/www/html/

RUN apt-get update; apt-get install curl zip

RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer

RUN composer install --no-scripts --no-autoloader
RUN composer dump-autoload --optimize