FROM php:8.3-fpm-alpine

# Install system dependencies and build tools
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
    sqlite \
    sqlite-dev \
    sqlite-libs \
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

# Install PHP core extensions
RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    pdo_sqlite \
    opcache \
    bcmath \
    zip

# Install GD extension with proper configuration
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Install remaining extensions
RUN docker-php-ext-install -j$(nproc) \
    mbstring \
    ctype \
    curl \
    fileinfo \
    intl \
    xml

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy application code
COPY . .

# Remove node_modules and vendor from copy (will be rebuilt)
RUN rm -rf vendor node_modules public/build || true

# Install PHP dependencies
RUN composer install --no-dev --no-interaction --optimize-autoloader

# Install Node.js and npm
RUN apk add --no-cache nodejs npm

# Install npm dependencies and build
RUN npm install --no-optional && npm run build

# Set permissions
RUN chown -R www-data:www-data /app && \
    chmod -R 755 /app/storage /app/bootstrap/cache

# Create necessary directories
RUN mkdir -p /app/storage/logs /app/storage/framework/cache /app/storage/framework/sessions /app/storage/framework/views

# Copy PHP configuration
COPY docker/php.ini /usr/local/etc/php/conf.d/laravel.ini

# Copy entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose port
EXPOSE 9000

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=40s --retries=3 \
    CMD php-fpm-healthcheck || exit 1

# Entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Start PHP-FPM
CMD ["php-fpm"]
