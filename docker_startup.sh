#!/bin/sh

# Startup Cron
cron

# Startup Supervisor
supervisord

# Startup PHP FPM daemon
/usr/local/sbin/php-fpm -c /usr/local/etc/php-fpm.conf
