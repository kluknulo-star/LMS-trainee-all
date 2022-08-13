#!/usr/bin/env bash

artisanCmd() {
  php artisan cache:clear
  php artisan view:clear
  php artisan config:cache
 # php artisan optimize:clear
}
# Add crontab entry
artisanCmd

(
  sudo echo "* * * * * www-data $(which php) /srv/app/artisan schedule:run >> /var/log/cron.log 2>&1"
  sudo echo "* * * * * root chown -R www-data:www-data /srv/app/storage/framework/ >> /var/log/cron.log 2>&1"
) >> /etc/crontab

sudo touch /var/log/cron.log
sudo chown www-data:www-data /var/log/cron.log
# отдаем права www-data, чтобы можно было туда писать
sudo chown www-data:www-data /etc/environment
printenv | grep -v \"no_proxy\" >> /etc/environment
sudo cron && tail -f /var/log/cron.log
#sudo supervisord
