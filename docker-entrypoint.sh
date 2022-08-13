#!/bin/bash
set -e

artisanCmd() {
  php artisan cache:clear
  php artisan view:clear
  php artisan config:cache
}

if [ "$1" = "php" ]; then
    echo
  else
    artisanCmd
    sudo supervisord
fi

#php artisan optimize:clear
#sudo supervisord
exec "$@"
