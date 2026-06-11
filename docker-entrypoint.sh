#!/bin/sh

# Replace the port in Nginx config with the PORT environment variable injected by Render (default to 80 if not set)
PORT=${PORT:-80}
sed -i "s/listen 80;/listen ${PORT};/g" /etc/nginx/http.d/default.conf

{
  echo "--- Startup Log $(date) ---"

  # Run database migrations automatically on startup (safe for Render free tier single instance)
  php artisan migrate --force

  # Create storage symbolic link if it doesn't exist to prevent broken uploads
  if [ ! -d "/var/www/html/public/storage" ] && [ ! -L "/var/www/html/public/storage" ]; then
      echo "Creating storage symbolic link..."
      php artisan storage:link
  fi


  # Run database seeding if the filieres table is empty or check fails (avoid running on every container spin-up)
  FILIERES_COUNT=$(php artisan tinker --execute="echo \DB::table('filieres')->count();")
  echo "Filiere count check output: '$FILIERES_COUNT'"

  if [ "$FILIERES_COUNT" = "0" ] || [ -z "$FILIERES_COUNT" ] || ! echo "$FILIERES_COUNT" | grep -qE '^[0-9]+$'; then
      echo "Database filieres table is empty or check failed. Seeding database..."
      php artisan db:seed --force
  else
      echo "Database already seeded (filieres count: $FILIERES_COUNT)."
  fi

  # Cache Laravel configuration, routes and views
  php artisan cache:clear
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
} > /var/www/html/storage/logs/startup.log 2>&1

# Start Node.js Signaling Server in background
echo "Starting Node.js Signaling & WebSocket Server..."
node server.cjs > /var/www/html/storage/logs/node_server.log 2>&1 &

# Start PHP-FPM in background
php-fpm -D

# Start Nginx in foreground
nginx -g "daemon off;"
