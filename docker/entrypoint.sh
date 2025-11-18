#!/bin/bash

set -e

# Script for initializing application on first run

echo "Initializing Koperasi App..."

# Check if .env exists
if [ ! -f .env ]; then
    echo "Creating .env file from .env.example..."
    cp .env.example .env
fi

# Always generate a new APP_KEY for each deployment
echo "Generating application key..."
php artisan key:generate --force

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

# Run migrate fresh with seed
echo "Running database migrations and seeding..."
php artisan migrate:fresh --seed --force

# Cache configuration
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link for public files
echo "Creating storage link..."
php artisan storage:link || true

echo "✓ Application initialized successfully!"

# Execute the main command
exec "$@"
