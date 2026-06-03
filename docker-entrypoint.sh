#!/bin/sh

# Replace the port in Nginx config with the PORT environment variable injected by Render (default to 80 if not set)
PORT=${PORT:-80}
sed -i "s/listen 80;/listen ${PORT};/g" /etc/nginx/http.d/default.conf

# Cache Laravel routes and views (config is not cached to allow runtime env var injection)
php artisan route:cache
php artisan view:cache

# Start PHP-FPM in background
php-fpm -D

# Start Nginx in foreground
nginx -g "daemon off;"
