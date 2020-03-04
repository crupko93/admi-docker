#!/bin/sh

### Ensure path existence
mkdir -p bootstrap
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p public/storage/framework/sessions
mkdir -p public/storage/framework/views
mkdir -p public/storage/framework/cache

### Ensure correct path permissions
[ -d bootstrap ]      && chmod -R 777 bootstrap
[ -d storage ]        && chmod -R 777 storage
[ -d public/storage ] && chmod -R 777 public/storage

### Make artisan executable
chmod +x artisan

### Install NPM dependencies
npm install

### Install Composer dependencies
composer install

### Regenerate Composer paths
composer dump-autoload

### Generate an initial encryption key
php artisan key:generate

### Generate an initial jwt key
php artisan jwt:secret

### Run Laravel DB migrations
php artisan migrate

### Run Laravel DB seeds
php artisan db:seed

