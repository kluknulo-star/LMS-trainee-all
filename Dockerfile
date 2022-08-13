FROM git.devspark.ru:5005/edu-platform/docker/images/php8.0.2-fpm-nginx1.14.2-supervisor:latest

RUN chown -R www-data:www-data /var/log/supervisor

COPY --chown=www-data:www-data . /srv/app

WORKDIR /srv/app

RUN chmod 0700 ./docker-entrypoint.sh ./cron-entrypoint.sh \
  && sed -r 's/^(worker_processes*)(.*)/\1  auto;/' -i /etc/nginx/nginx.conf \
  && sed -r 's/^(user*)(.*)/\1  www-data www-data;/' -i /etc/nginx/nginx.conf \
  && sed -r '\|/etc/nginx/sites-enabled|d' -i /etc/nginx/nginx.conf

COPY docker.d/default.conf /etc/nginx/conf.d/default.conf
COPY docker.d/supervisord.conf /etc/supervisor/supervisord.conf
COPY docker.d/php-fpm.conf /usr/local/etc/php-fpm.d/zz-www.conf

USER www-data
