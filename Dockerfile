FROM docker/compose:1.24.0

WORKDIR /app

# installs docker
COPY --from=docker:latest /usr/local/bin/docker /usr/local/bin/docker

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
RUN curl -L https://github.com/fireworkweb/fwd/blob/php/builds/fwd?raw=true -o /usr/local/bin/fwd
RUN chmod +x /usr/local/bin/fwd

ENTRYPOINT [ "" ]
CMD [ "/usr/local/bin/fwd" ]
