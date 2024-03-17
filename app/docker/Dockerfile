FROM mgdteam/php:8.2-fpm-nginx

ARG ENVIRONMENT

RUN apk update --update-cache && \
    apk upgrade --available && \
    apk --no-cache add \
        freetype-dev \
        icu-dev \
        icu-data-full \
        linux-headers

# Install additional php extensions
#RUN docker-php-ext-install -j$(nproc) \
#    intl \
#    calendar \
#    sockets

# Configure services
ADD ./php/php.ini-${ENVIRONMENT} /usr/local/etc/php/php.ini
