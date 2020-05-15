FROM docker/compose:1.24.0

# default docker target assuming a docker:dind service is avaibable
ENV DOCKER_DRIVER "overlay2"
ENV DOCKER_HOST "tcp://docker:2375"
ENV DOCKER_TLS_CERTDIR ""

WORKDIR /app

# installs PHP and dependencies
RUN apk add --no-cache \
    curl \
    openssh-client \
    php-cli \
    php-phar \
    php-openssl \
    php-json \
    php-tokenizer \
    php-fileinfo \
    php-iconv \
    php-mbstring \
    php-posix

COPY builds/fwd /usr/local/bin/fwd

ENTRYPOINT [ "" ]

CMD [ "fwd" ]
