#!/bin/sh

composer install

php bin/console d:m:m --no-interaction

php-fpm -D

nginx -g 'daemon off;'