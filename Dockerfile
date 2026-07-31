FROM php:8.2-apache

# Install and enable mysqli and pdo_mysql extensions for MySQL connection
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli pdo_mysql

# Enable apache mod_rewrite
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80
