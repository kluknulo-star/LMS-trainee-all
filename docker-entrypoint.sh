#!/bin/bash
set -e

artisanCmd() {
  php artisan cache:clear
  php artisan view:clear
  php artisan config:cache
}

artisanCmd
sudo supervisord

exec "$@"
