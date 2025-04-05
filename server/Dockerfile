# Use the official PHP image as a base image
FROM php:8.1-apache

# Enable mod_rewrite
RUN a2enmod rewrite

# Install the PDO MySQL extension
RUN docker-php-ext-install pdo_mysql

# Copy the current directory contents into the container at /var/www/html
COPY . /var/www/html/

# Set AllowOverride to All for .htaccess support
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Expose port 80 to the host
EXPOSE 80