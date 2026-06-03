#!/bin/sh

# Replace the port in Nginx config with the PORT environment variable injected by Render (default to 80 if not set)
PORT=${PORT:-80}
sed -i "s/listen 80;/listen ${PORT};/g" /etc/nginx/http.d/default.conf

# Run database migrations automatically on startup (safe for Render free tier single instance)
php artisan migrate --force

# Run database seeding if the filieres table is empty (avoid running on every container spin-up)
FILIERES_COUNT=$(php artisan tinker --execute="echo \DB::table('filieres')->count();" 2>/dev/null)
if [ "$FILIERES_COUNT" = "0" ]; then
    echo "Database filieres table is empty. Seeding..."
    php artisan db:seed --force
else
    echo "Database already seeded (filieres count: $FILIERES_COUNT)."
fi

# Cache Laravel routes and views
php artisan route:cache
php artisan view:cache

# Start PHP-FPM in background
php-fpm -D

# Start Nginx in foreground
nginx -g "daemon off;"
