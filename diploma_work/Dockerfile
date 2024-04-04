# Use a smaller base image
FROM php:8.2-alpine

# Set working directory inside the container
WORKDIR /var/www/html

# Install PHP extensions and dependencies
RUN apk add --no-cache \
        libpng \
        libpng-dev \
        libjpeg-turbo \
        libjpeg-turbo-dev \
        freetype \
        freetype-dev \
        zip \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_mysql

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer.lock and composer.json to install PHP dependencies
COPY composer.lock composer.json /var/www/html/

# Install PHP dependencies
RUN composer install --no-scripts --no-autoloader

# Copy the rest of the application
COPY . /var/www/html

# Expose port 8000 to the outside world
EXPOSE 8000

# Start the Laravel development server
CMD ["php", "artisan", "serve", "--host", "0.0.0.0", "--port", "8000"]
