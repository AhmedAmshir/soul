FROM php:8.2-fpm-buster

RUN apt-get update && apt-get install -y \
    libmemcached-dev \
    zlib1g-dev \
    libssl-dev \
    wget \
    curl \
    libxml2-dev \
    vim \
    libzip-dev \
    git \
    zip \
    unzip \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libpq-dev \
    gnupg \
    cron

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install gd mbstring bcmath pdo pdo_mysql mysqli
RUN docker-php-ext-enable mysqli

WORKDIR /usr/local/etc/php/conf.d/
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Install Node.js
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get update \
    && apt-get install -y nodejs

RUN chown -R www-data:www-data /var/www/html/
RUN chmod -R g+w /var/www/html/

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

ADD .crontab /etc/cron.d/cron
RUN chmod 0644 /etc/cron.d/cron
RUN touch /var/log/cron.log

WORKDIR /var/www/html/soul
CMD cron && docker-php-entrypoint php-fpm
