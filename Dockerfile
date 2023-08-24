FROM phpdockerio/php:8.2-fpm
WORKDIR "/application"

COPY php-config/php-ini-overrides.ini /etc/php/8.2/fpm/conf.d/99-overrides.ini

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install webp php8.2-pgsql php8.2-redis php8.2-xdebug php8.2-intl php8.2-gd \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install git
RUN apt-get update \
    && apt-get -y install git \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./composer.* ./
COPY ./.env ./

RUN /usr/bin/composer install --no-scripts

RUN usermod -u 1000 www-data && groupmod --non-unique --gid 1000 www-data

RUN chown -R www-data:www-data /application
