#!/bin/bash

set -e

# Script for initializing application on first run

echo "Initializing Koperasi App..."

# Check if .env exists
if [ ! -f .env ]; then
    echo "Creating .env file from .env.example..."
    cp .env.example .env
fi

# Generate app key if not set
if ! grep -q "^APP_KEY=base64:" .env || [ -z "$(grep '^APP_KEY=' .env | cut -d= -f2)" ]; then
    echo "Generating application key..."
    php artisan key:generate
fi

# Create required directories
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache
mkdir -p database

# Create SQLite database if it doesn't exist
if [ ! -f database/database.sqlite ]; then
    echo "Creating database..."
    touch database/database.sqlite
fi

# Set permissions
chown -R www-data:www-data storage bootstrap/cache database 2>/dev/null || true
chmod -R 755 storage bootstrap/cache database 2>/dev/null || true

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Cache configuration
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Application initialized successfully!"

# Execute the main command
exec "$@"
