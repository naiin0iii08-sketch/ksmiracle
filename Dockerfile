FROM php:8.2-apache

# Install PostgreSQL client and PHP extensions
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set the working directory to Apache document root
WORKDIR /var/www/html

# Copy the application files to the container from the frontend folder
COPY frontend/ /var/www/html/

# Expose port 80
EXPOSE 80

# Configure Apache to listen on $PORT provided by Render
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf
