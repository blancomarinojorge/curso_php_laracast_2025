FROM php:8.2-apache

# Install required extensions (mysql)
RUN docker-php-ext-install pdo pdo_mysql

RUN a2enmod rewrite

# Enable .htaccess override
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf
