FROM php:8.1.4-alpine

RUN echo 'https://dl-cdn.alpinelinux.org/alpine/v3.15/main' > /etc/apk/repositories  && \
    echo '@testing https://dl-cdn.alpinelinux.org/alpine/edge/testing' >> /etc/apk/repositories && \
    echo '@community https://dl-cdn.alpinelinux.org/alpine/v3.15/community'
RUN apk add curl

ENV \
    COMPOSER_ALLOW_SUPERUSER="1" \
    COMPOSER_HOME="/tmp/composer"

COPY --from=composer:2.3 /usr/bin/composer /usr/bin/composer

RUN set -x \
    && apk add --no-cache binutils git \
    && apk add --no-cache --virtual .build-deps autoconf pkgconf make g++ gcc 1>/dev/null \
    # install xdebug (for testing with code coverage), but do not enable it
    && pecl install xdebug-3.1.4>/dev/null \
    && apk del .build-deps \
    && mkdir /src ${COMPOSER_HOME} \
    && ln -s /usr/bin/composer /usr/bin/c \
    && chmod -R 777 ${COMPOSER_HOME} \
    && composer --version \
    && php -v \
    && php -m

WORKDIR /src
