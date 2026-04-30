#!/bin/bash

# Clear all caches first
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
php artisan migrate --force

# Create storage link
php artisan storage:link 2>/dev/null || true

# Start Apache in foreground
apache2-foreground
