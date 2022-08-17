#!/usr/bin/env bash

artisanCmd() {
  php artisan cache:clear
  php artisan view:clear
  php artisan config:cache
}
# Add crontab entry
artisanCmd
echo "* * * * * $(which php) /srv/app/artisan schedule:run >> /var/log/cron.log 2>&1" >> /srv/app/cronjob
crontab /srv/app/cronjob
rm /srv/app/cronjob
sudo chmod 646 /etc/environment
printenv | grep -v \"no_proxy\" >> /etc/environment
sudo chmod 644 /etc/environment
sudo cron && tail -f /var/log/cron.log
