# exception-with-context: Development and testing

The development environment uses PHP 8.5 with Xdebug, Composer, and nginx.
Install Docker Engine and the Docker Compose v2 plugin; no local PHP installation is required.
The library's minimum requirement remains PHP 7.4.

## First setup

Run these commands from the repository root:

```sh
docker compose -f docker-compose.dev.yml up --build -d
```

PHP initializes the shared `test-app` Docker volume with the example and its installed dependencies.
nginx mounts the same volume read-only. No manual copying is required. An existing `test-app-src`
directory is no longer used; any local edits in it are preserved.

Open <http://localhost:8080/index.php>. The page shows context retrieval and replacement for
both `ExceptionWithContext` and a custom exception using `ExceptionWithContextTrait`.

Run the same example from the CLI:

```sh
docker compose -f docker-compose.dev.yml exec -e XDEBUG_MODE=off exception-with-context-php php /var/www/html/index.php
```

To use a different HTTP port, set `HOST_NGINX_PORT` when starting the services:

```sh
HOST_NGINX_PORT=8280 docker compose -f docker-compose.dev.yml up -d
```

## How it works

The `php-runtime` build target provides PHP 8.5, Composer 2.11.1, and Xdebug 3.5.3. It uses
PHP's bundled development configuration with `E_ALL`, including deprecation reporting.

The `development` target copies the example from `deploy/data/php/root/var/www/html` and
installs its committed Composer lock file. The library's `composer.json` and `src/` are copied
from this checkout into `/package-src` during the build. No published package archive is downloaded.

At runtime, Compose mounts this working tree read-only at `/package-src`. The example's Composer
autoloader links to that path, so requests use the current library source, including uncommitted
changes. The example volume is mounted at `/var/www/html` in both services.

## Rebuild or refresh the example

Library changes under `src/` take effect immediately through the `/package-src` mount.
After changing the example, its dependencies, or the Dockerfile, rebuild and recreate its volume:

```sh
docker compose -f docker-compose.dev.yml down --volumes
docker compose -f docker-compose.dev.yml up --build -d
```

`down --volumes` deletes the generated example application and its runtime files. It does not
delete repository files. To stop the environment while preserving the volume, omit `--volumes`.

## Logs and debugging

```sh
docker compose -f docker-compose.dev.yml logs -f
```

PHP-FPM and nginx write their logs to the container output. Xdebug settings are in
`deploy/data/php/root/usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini`; the IDE listener
uses port 9000 on `host.docker.internal`.

For CLI debugging with an IDE server mapping:

```sh
docker compose -f docker-compose.dev.yml exec -e PHP_IDE_CONFIG=serverName=localhost exception-with-context-php php /var/www/html/index.php
```

Stop the environment with:

```sh
docker compose -f docker-compose.dev.yml down
```
