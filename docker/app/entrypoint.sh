#!/usr/bin/env sh
set -e

composer install --no-interaction --prefer-dist --no-progress

php artisan key:generate --force
php artisan config:clear
php artisan migrate --force

exec "$@"
