#!/bin/sh
set -e

if [ "$ENABLE_XDEBUG" == "true" ]; then
    docker-php-ext-enable xdebug >> /dev/null 2>&1

    if [ $? != "0" ]; then
        echo "[ERROR] An error happened enabling xdebug"

        exit 1
    fi
fi

# Run as current user
if [ ! -z "$ASUSER" ] && [ "$ASUSER" != "0" ]; then
    usermod -u $ASUSER developer
fi

if [ "$1" = "php-fpm" ]; then
    exec "$@"
else
    exec su-exec developer "$@"
fi
