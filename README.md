# fireworkweb/fwd

[![Build Status](https://travis-ci.com/fireworkweb/fwd.svg?branch=php)](https://travis-ci.com/fireworkweb/fwd)
[![codecov](https://codecov.io/gh/fireworkweb/fwd/branch/php/graph/badge.svg)](https://codecov.io/gh/fireworkweb/fwd)

## Installation & Requirements

You need to have PHP 7.2+ installed in order to run `fwd`.

```bash
curl -L https://github.com/fireworkweb/fwd/raw/php/builds/fwd -o /usr/local/bin/fwd
chmod +x /usr/local/bin/fwd
```

* For Release Candidate and testing the latest features please checkout https://github.com/fireworkweb/fwd/issues/70#issuecomment-554606414.
* Don't have and don't want to have PHP installed locally? Checkout and follow https://github.com/fireworkweb/fwd/issues/21 for 100% Docker usage, thus not requiring PHP.

## Getting started

#### Use in a new project

`fwd` is great for running Docker Compose based projects. If you wanna use it in your project simply go to the root folder and use:

```base
fwd install
```

This will create the `.fwd` (environment variables) and `docker-compose.ym` (services definitions) files in your project (you probably want to have them versioned).

You should review the variables and services in both files to best suit them to your project needs. The out-of-the-box configuration is great for PHP Laravel applications. Special attention to the environment variables flexibity (check out the section down this README).

#### Simple usage

To start the services:

```bash
fwd start
```

To stop the services:
```bash
fwd stop
```

Note: stopping will destroy all your containers, as they should all be ephemeral. Don't worry, important data should always be stored in volumes so they are kept amongst containers generations.

#### Other commands

There is a bunch of commands delivered out of the box. You can check a  list of all of them by running `fwd` without other arguments to see the help message.

A couple of examples:

```
# PHP projects
fwd composer install # runs PHP Composer
fwd artisan migrate:fresh --seed # runs Laravel artisan CLI tool

# JS projects
fwd yarn install # runs Yarn install
fwd yarn dev     # runs a package.json defined script

# Custom docker and docker-compose
fwd docker-compose logs -f http # tails the logs of the http service
```

## Environment variables

`fwd` is very flexible for you to tweak and change behaviors using environment variables. Variables like `FWD_HTTP_PORT` that holds the port `http` service will bind to, for example, can easily be changed in a number of ways, find the one that best fits your needs.

The precedence is as follows (from the highest to the lowest precedence):

1. In-line/exported environment variables. (i.e `FWD_DEBUG=1 fwd ...`)
2. `.env` file in the current working directory (dotenv file format; usually not versioned in your project).
3. `.fwd` file in the current working directory (this file holds project specific `fwd` settings that usually are versioned with the project).
4. `$HOME/.fwd` file; so you can have user-system specific changes easily across the board if needed.
5. Defaults `.fwd` distributed file bundled in `fwd` CLI; subject to changes with new versions.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
