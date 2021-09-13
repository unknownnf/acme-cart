FROM php:8-cli

# Install required system packages
RUN apt-get update && \
    apt-get -y install \
            git \
            zlib1g-dev \
            libmemcached-dev \
            libpq-dev \
            libssl-dev \
            libxml2-dev \
            libzip-dev \
            unzip \
        --no-install-recommends && \
        apt-get clean && \
        rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install php extensions
RUN docker-php-ext-install \
    bcmath \
    mysqli \
    pdo pdo_mysql pdo_pgsql \
    soap \
    sockets \
    zip

# Configure php
RUN echo "date.timezone = UTC" >> /usr/local/etc/php/php.ini

# Install composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
        --filename=composer \
        --install-dir=/usr/local/bin

# Add source-code
COPY . /repo

# Prepare application
WORKDIR /repo

# Install modules
RUN composer require --no-update \
codeception/module-apc \
codeception/module-asserts \
codeception/module-cli \
codeception/module-db \
codeception/module-filesystem \
codeception/module-ftp \
codeception/module-memcache \
codeception/module-mongodb \
codeception/module-phpbrowser \
codeception/module-redis \
codeception/module-rest \
codeception/module-sequence \
codeception/module-soap \
codeception/module-webdriver && \
composer update --no-dev --prefer-dist --no-interaction --optimize-autoloader --apcu-autoloader

RUN chmod -R 755 /repo/vendor/bin/codecept

ENV PATH /repo:/repo/vendor/bin:${PATH}
ENTRYPOINT ["codecept"]

RUN mkdir /project
WORKDIR /project