<p align="center">
    <a href="https://fireworkweb.com/"><img width="200" src="https://fireworkweb.com/wp-content/uploads/2019/01/logo-firework.png"></a><br>
    FireworkWwebDocker is a Docker-based dev environment for Laravel.
</p>

---

# Getting Started

```bash
#clone the repository
git clone https://github.com/fireworkweb/fwd.git

#start container
./fwd start
#stop container
./fwd down
```
---

# Everyday Usage

```bash
#php commands
./fwd php
#artisan commands
./fwd art
#composer commands
./fwd composer
#testing commands
./fwd test

#dump mysql db
./fwd dump
#restore mysql db - it expects the dump file path as the second argument
./fwd restore
#import mysql db from container - it expects the file path as second argument
./fwd import

#run mysql
./fwd mysql

#node commands
./fwd node
#npm commands
./fwd npm
#yarn commands
./fwd yarn
#run gulp -- csfixer + phpmd + phpcpd
./fwd gulp
```

## Documentation

For full documentation on how to create new commands, visit [fireworkweb.com](https://fireworkweb.com/).

