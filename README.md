# fireworkweb/fwd

[![Build Status](https://travis-ci.com/fireworkweb/fwd.svg?branch=php)](https://travis-ci.com/fireworkweb/fwd)
[![codecov](https://codecov.io/gh/fireworkweb/fwd/branch/php/graph/badge.svg)](https://codecov.io/gh/fireworkweb/fwd)

## Pre Requisites

Docker
Docker-Compose
PHP >= 7.1.3

## Installation

```bash
curl -L https://github.com/fireworkweb/fwd/raw/php/builds/fwd -o /usr/local/bin/fwd
chmod +x /usr/local/bin/fwd
```
Inside of your project:

Recommended configuration of your `.env` file:

```bash
DB_CONNECTION=mysql
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=redis
```

```bash
fwd install
```

## Usage

```bash
fwd start
fwd composer install
fwd artisan migrate:fresh --seed
fwd yarn install
fwd yarn dev
fwd down
```
