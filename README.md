# Admin [App]

This README describes the steps needed to run the **Admin** app

## Pre-requisites

### 1. System requirements

- Node.js
- Composer

##### For Capistrano deployment:

- Ruby >= 2.3
- Bundler >= 1.15

### 2. Cloning

The standard way to install this project is to [clone and setup the Docker environment](https://github.com/FLYGOPROJECT/admin-docker#3-cloning-this-repository-and-attached-projects).

### 3. Setup

**1**. Copy and edit the environment file:

```bash
$ cd app
$ cp .env.example .env
$ nano .env
```

**2**. Log into MySQL locally and create a project database:

```
Host: 127.0.0.1
Port: 33062
Username: root
Password: root
```
Create DB `admin` (default .env name)

**3**. __[Open a shell to the App Docker instance](https://github.com/crupko93/admin-docker#2-open-a-shell-on-a-container)__ and run:

```bash

# 1. Install Composer dependencies
[Docker]$ composer install

# 2. Generate an initial encryption key
[Docker]$ php artisan key:generate

# 3. Generate an initial jwt key
[Docker]$ php artisan jwt:secret

# 4. Install NPM dependencies
[Docker]$ npm install

# 5. Run Laravel DB migrations
[Docker]$ php artisan migrate

# 6. Make sure `storage/framework/cache`, `storage/framework/sessions`, `storage/framework/views` directories exist. 
[Docker]$ mkdir -p storage/framework/{sessions,views,cache}
[Docker]$ chmod -R 777 bootstrap && chmod -R 777 storage && chmod -R 777 public/storage

# 7. Run Laravel DB seeders
[Docker]$ php artisan db:seed

# If you have a unix-like terminal then steps 1-7 have a shortcut:
[Docker]$ sh setup.sh

```

```bash
### The following commands should be run outside of Docker context:

# Build project assets once
$ npm run dev

# Build project assets continuously
$ npm run hot
```

## Deployment [Capistrano]

```bash
# Deploying to `staging`
# (the target branch will be requested from the user as input; any branch can be deployed to staging)
$ bundle exec cap staging deploy

# Deploying to `production`
# (for safety reasons production deployments are always affixed to the master branch)
$ bundle exec cap production deploy
```

__Important:__ Before deployments, if any assets have been modified, don't forget to run `npm run production` and then push the built assets to the target branch.

> Note: You can also run individual Capistrano tasks. To get a list of available tasks, run `cap -T` from your terminal.

> Note: Production deployments are restricted to the `master` branch. It is highly discouraged to deploy development branches to a live site.

> Example:
>
> - `$ bundle exec cap staging composer:install`
>
> - `$ bundle exec cap staging laravel:migrate`


## PHPUnit Testing

1. Create a new database and name it `admin_test`

2. While in Docker shell run `composer test` to run all tests.
