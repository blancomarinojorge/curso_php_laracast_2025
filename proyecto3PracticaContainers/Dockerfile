FROM php:8.2-apache

# Install required extensions (mysql)
RUN docker-php-ext-install pdo pdo_mysql

RUN a2enmod rewrite

# Enable .htaccess override
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf


## change document root so only files in public are accessible
# Change Apache DocumentRoot to /var/www/html/public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Update Apache config to use the new document root
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}/../!g' /etc/apache2/apache2.conf
