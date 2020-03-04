FROM php:7.4.1-fpm

# Install global system packages
RUN apt-get update && apt-get install -my \
    bzip2 \
    ca-certificates \
    cron \
    curl \
    git \
    gnupg \
    libatk-bridge2.0.0 \
    libfontconfig \
    libfreetype6-dev \
    libgtk-3.0 \
    libjpeg62-turbo-dev \
    libmagickwand-dev \
    libmemcached-dev \
    libmcrypt-dev \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    nano \
    supervisor \
    wget \
    zlib1g-dev

# Install Node.js 10.x
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash \
    && apt-get install -y nodejs\
    && mkdir -p /var/www/.config \
    && chmod 777 -R /var/www/.config

# Install common PHP extensions
RUN docker-php-ext-install -j$(nproc) iconv mysqli pdo pdo_mysql soap zip exif bcmath

# Install `mcrypt` PHP extension
RUN pecl install mcrypt-1.0.3 \
    && docker-php-ext-enable mcrypt

# Install `gd` PHP extension
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Install `memcached` PHP extension
RUN pecl install memcached-stable && docker-php-ext-enable memcached

# Install `imagick` PHP extension
RUN pecl install imagick && docker-php-ext-enable imagick

# Install `redis` PHP extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy PHP configuration file
COPY php.ini /usr/local/etc/php/

# Initialize Cron jobs
COPY cronjobs /etc/cron.d/cronjobs
RUN chmod 0644 /etc/cron.d/cronjobs
RUN crontab /etc/cron.d/cronjobs
RUN touch /var/log/cron.log

# Copy Bash profiles
COPY .bashrc.global /etc/bash.bashrc
COPY .bashrc.root /root/.bashrc

# Copy Supervisor profiles
COPY supervisor-laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf

# Copy and run startup script
COPY docker_startup.sh /etc/docker_startup.sh
RUN chmod 0777 /etc/docker_startup.sh
CMD /etc/docker_startup.sh
