#!/bin/sh
set -e

php artisan migrate --force
php artisan db:seed --class=ProductSeeder --force

# Scheduler: every minute schedule:run (background, www-data user-a)
(while true; do runuser -u www-data -- php artisan schedule:run --no-interaction > /dev/null; sleep 60; done) &

exec apache2-foreground