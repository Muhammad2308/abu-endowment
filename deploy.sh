#!/usr/bin/env bash
# deploy.sh — run on the production server from the project root
# Usage: bash deploy.sh

set -e

echo "==> Pulling latest code..."
git pull origin master

echo "==> Installing/updating Composer dependencies..."
composer install --no-dev --optimize-autoloader

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Setting up storage directories and symlink..."
php artisan app:setup-storage

echo "==> Fixing ownership and permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

echo "==> Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Done."
