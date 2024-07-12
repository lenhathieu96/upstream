# Use the official PHP image with Ubuntu 20.04
FROM php:8.3-cli

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libpq-dev \
        libjpeg-dev \
        libpng-dev \
        libfreetype6-dev \
        libonig-dev \
        libzip-dev \
        libmcrypt-dev \
        zip \
        supervisor \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        gd \
        mysqli \
        pdo_mysql \
        zip \
    && pecl install mcrypt \
    && docker-php-ext-enable mcrypt

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy existing application directory contents
COPY . .

# Install Laravel dependencies
RUN composer install --no-scripts

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage

# Expose port 80 and start PHP server
EXPOSE 80
CMD ["php", "artisan", "serve", "--port=80"]
