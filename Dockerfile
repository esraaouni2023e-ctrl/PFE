FROM php:8.1-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    nodejs \
    npm \
    git \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    bash

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql gd zip bcmath opcache

# Configure PHP limits and timeouts + OPcache performance settings
RUN echo "max_execution_time=600" > /usr/local/etc/php/conf.d/custom-limits.ini \
    && echo "memory_limit=256M" >> /usr/local/etc/php/conf.d/custom-limits.ini \
    && echo "post_max_size=100M" >> /usr/local/etc/php/conf.d/custom-limits.ini \
    && echo "upload_max_filesize=100M" >> /usr/local/etc/php/conf.d/custom-limits.ini \
    && echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/custom-limits.ini \
    && echo "opcache.enable_cli=1" >> /usr/local/etc/php/conf.d/custom-limits.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/custom-limits.ini \
    && echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/custom-limits.ini \
    && echo "opcache.max_accelerated_files=20000" >> /usr/local/etc/php/conf.d/custom-limits.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/custom-limits.ini \
    && echo "opcache.revalidate_freq=0" >> /usr/local/etc/php/conf.d/custom-limits.ini \
    && echo "opcache.fast_shutdown=1" >> /usr/local/etc/php/conf.d/custom-limits.ini

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy codebase
COPY . .

# Run composer installation
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Install NPM dependencies & build assets
RUN npm install && npm run build

# Copy Nginx config
COPY nginx.conf /etc/nginx/http.d/default.conf

# Setup directory permissions for Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy Entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose port 80 (will be replaced dynamically by entrypoint script with $PORT)
EXPOSE 80

CMD ["/usr/local/bin/docker-entrypoint.sh"]
