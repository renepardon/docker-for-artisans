FROM composer:latest as dependencies

COPY database/ database/
COPY composer.json composer.json
COPY composer.lock composer.lock

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --no-dev

# Get a fresh base image and copy the application and vendor directory to it
FROM php:7.3.11-apache-stretch
LABEL maintainer="rene.pardon@boonweb.de"

RUN mkdir -p /var/www/html/public && chown -R www-data:www-data /var/www
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN buildDeps=" \
        default-libmysqlclient-dev \
        libcurl4-openssl-dev \
        libbz2-dev \
        libmemcached-dev \
        libsasl2-dev \
        pkg-config \
        libssl-dev \
    " \
    runtimeDeps=" \
        curl \
        supervisor \
        libfreetype6-dev \
        libicu-dev \
        libjpeg-dev \
        libldap2-dev \
        libmemcachedutil2 \
        libpng-dev \
        libpq-dev \
        libxml2-dev \
        libzip-dev \
    " \
    && apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y $buildDeps $runtimeDeps \
    && docker-php-ext-install bcmath bz2 calendar iconv intl mbstring mysqli opcache pdo_mysql pdo_pgsql pgsql soap zip \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd \
    && docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ \
    && docker-php-ext-install ldap \
    && docker-php-ext-install exif \
    && pecl install memcached redis \
    && docker-php-ext-enable memcached.so redis.so \
    && apt-get purge -y --auto-remove $buildDeps \
    && apt-get clean \
    && rm -r /var/lib/apt/lists/*

COPY --chown=www-data --from=dependencies /app/vendor/ /var/www/html/vendor/
COPY --chown=www-data . /var/www/html/

COPY ./docker/web/000-default.conf /etc/apache2/sites-enabled/000-default.conf
RUN a2enmod rewrite && service apache2 restart
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

