cd /var/www/html
if [ ! -d "./vendor" ] || [ -z "$( ls -A './vendor' )" ]; then
   composer install && php artisan key:generate
fi

if [ ! -d "./node_modules" ] || [ -z "$( ls -A './node_modules' )" ]; then
   npm ci
fi

sh /var/www/start/sync.sh

/usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf