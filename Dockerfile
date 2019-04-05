FROM docker/compose:1.24.0

WORKDIR /app

# installs PHP and dependencies
RUN apk add --no-cache \
    curl \
    php-cli \
    php-phar \
    php-openssl \
    php-json \
    php-tokenizer \
    php-fileinfo \
    php-iconv \
    php-mbstring \
    php-posix

# installs latest fwd version
RUN curl -L https://github.com/fireworkweb/fwd/raw/php/builds/fwd -o /usr/local/bin/fwd
RUN chmod +x /usr/local/bin/fwd

ENTRYPOINT [ "" ]

CMD [ "fwd" ]
