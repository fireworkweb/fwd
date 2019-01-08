FROM php:7.2-fpm-alpine

ENV COMPOSER_ALLOW_SUPERUSER 1
ARG ASUSER

WORKDIR /var/www/html

RUN apk add --no-cache su-exec bash git openssh-client icu libpng freetype libjpeg-turbo libzip-dev shadow && \
    apk add --no-cache --virtual build-dependencies icu-dev libxml2-dev freetype-dev \
        libpng-dev libjpeg-turbo-dev g++ make autoconf libsodium-dev libedit-dev curl-dev && \
    docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && \
    docker-php-ext-install -j$(nproc) gd curl pdo pdo_mysql mbstring xml zip bcmath soap intl readline && \
    apk del build-dependencies && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    rm -rf /tmp/* && rm -fr /src

RUN adduser -D -u 1337 developer && \
    sed -i "s/user\ \=.*/user\ \= developer/g" /usr/local/etc/php-fpm.d/www.conf && \
    composer global require hirak/prestissimo

RUN mkdir -p /usr/src/php/ext && \
    git clone https://github.com/nikic/php-ast /usr/src/php/ext/ast && \
    docker-php-ext-configure ast && \
    docker-php-ext-install ast

COPY entrypoint /entrypoint
RUN chmod +x /entrypoint

EXPOSE 9000

ENTRYPOINT [ "/entrypoint" ]
CMD [ "php-fpm" ]
