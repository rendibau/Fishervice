FROM php:8.0-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install the mysqli extension
RUN docker-php-ext-install mysqli

# Set the correct working directory
WORKDIR /var/www/html

# Set permissions to allow access
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Add Apache configuration to allow access to directories
RUN echo '<Directory /var/www/html>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/docker-directory.conf && \
    a2enconf docker-directory