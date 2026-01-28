FROM php:8.4-fpm-alpine3.19 as base

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN apk update 

RUN apk add \
    bash \
    zlib-dev \
    libxml2-dev \
    libzip-dev \
    libpng-dev \
    libpq-dev \
    nano \
    nginx \
    icu-dev \
    && install-php-extensions \
    zip \
    intl \
    pdo \
    pdo_mysql \
    gd \
    2>&1 >/dev/null


COPY ./docker/php-fpm-overrides.conf /usr/local/etc/php-fpm.d/zz_php-fpm-overrides.conf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh

RUN chmod -x /usr/local/bin/docker-entrypoint.sh

RUN chmod 777 /usr/local/bin/docker-entrypoint.sh

ENV FPM_MAX_CHILDREN=16
ENV FPM_START_SERVERS=4
ENV FPM_MIN_SPARE_SERVERS=2
ENV PHP_MEMORY_LIMIT=1024M

COPY ./docker/nginx/default.conf /etc/nginx/nginx.conf

ENTRYPOINT [ "docker-entrypoint.sh" ]

WORKDIR /code

FROM base as builder

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install --prefer-dist --no-progress --no-interaction --no-dev --no-scripts

COPY ./ ./

FROM base AS dist

COPY --from=builder /code/ ./