#!/bin/bash

# Clear config cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
php artisan migrate --force

# Start Apache in foreground
apache2-foreground
