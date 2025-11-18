#############################################
# STAGE 1: Build Frontend Assets (Node 20 LTS)
#############################################
FROM node:20-alpine AS build-stage
WORKDIR /app

# Install npm dependencies
COPY package*.json ./
RUN npm install --no-optional

# Copy all project files
COPY . .

# Build assets
RUN npm run build


#############################################
# STAGE 2: PHP 8.3 Runtime (Laravel)
#############################################
FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    curl \
    wget \
    git \
    zip \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    postgresql-dev \
    sqlite sqlite-dev sqlite-libs \
    mysql-client \
    oniguruma-dev \
    icu-dev \
    autoconf \
    g++ \
    make \
    pkgconfig \
    linux-headers \
    libxml2-dev \
    zlib-dev \
    curl-dev \
    libzip-dev

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) \
    pdo pdo_mysql pdo_pgsql pdo_sqlite \
    opcache bcmath zip

# Build GD extension
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Remaining PHP extensions
RUN docker-php-ext-install -j$(nproc) \
    mbstring ctype curl fileinfo intl xml

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Working directory
WORKDIR /app

# Copy PHP app code
COPY . .

# Clear old vendor
RUN rm -rf vendor || true

# Install PHP deps
RUN composer install --no-dev --no-interaction --optimize-autoloader

# Copy built assets from Node build-stage
COPY --from=build-stage /app/public/build /app/public/build

# Laravel storage dirs
RUN mkdir -p storage/logs storage/framework/{cache,sessions,views}

# Fix permissions
RUN chown -R www-data:www-data /app \
    && chmod -R 755 storage bootstrap/cache

# Copy PHP config
COPY docker/php.ini /usr/local/etc/php/conf.d/laravel.ini

# Entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose PHP-FPM
EXPOSE 9001

# Healthcheck
HEALTHCHECK --interval=30s --timeout=10s --start-period=30s --retries=3 \
    CMD php-fpm-healthcheck || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
