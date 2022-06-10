# exception-with-context: Development end testing

## Launch it

To make testing and development simple project contains `php.dev.Dockerfile` and `docker-compose.dev.yml`.

You are not required to install PHP, Apache or nginx.

The whole pipeline for the first setup is the following:
1. You need to build docker image with a command `docker build -f php.dev.Dockerfile -t exception-with-context:latest .`.
2. After that you need to check `docker image ls` output and assure that image `exception-with-context-php:latest` was built.
3. Create docker container with the command
`docker create --name exception-with-context-php-tmp-for-copy exception-with-context-php:latest`.
*But don't launch docker container!* You need the container only for file copying.
4. Copy the content of the `/var/www/html` folder from the container to the host machine. Run it from the root path
of this repository - `docker cp -L exception-with-context-php-tmp-for-copy:/var/www/html test-app-src`.
5. Delete container for copying `docker rm -f exception-with-context-php-tmp-for-copy`.
6. Run containers after that. `docker-compose -f docker-compose.dev.yml -p exception-with-context up`.

> `docker-compose` without `--build` flag will not rebuild the Docker image created with `docker build`. Add this flag
> if yuu need rebuild image after initial launch.

`docker-compose` will mount all necessary volumes to make it possible develop and debug application.

> By default, nginx listens port 8080. You may change this port by specifying environment variable `HOST_NGINX_PORT`.
> For example, to run nginx on port 8280 run
> `HOST_NGINX_PORT=8280 docker-compose -f docker-compose.dev.yml -p exception-with-context up`

`docker-compose` will run in foreground, so you will see `stderr` logs and may test this application.
If you want you may run containers in a background mode (add `-d` option after `docker-compose up`).


### Recreation of containers

If you want to update repository and recreate docker image, for example, if new version of `test-app` was released,
you can remove the whole `test-app-src` folder and redo all the steps in the ["Launch it"](#launch-it) section.

### Explanation

1`composer`, `xdebug` and all needed extensions are installed during the Docker build phase.
2. `composer` installs `yii` boilerplate project.
3. Next step in the build pipeline copies custom files from the repo
   (`deploy/data/php/root/var/www/html`) to the image.
4. Custom files contains php scripts and updated `composer.json`, so `composer update` will be executed.
5. Custom `composer.json` specifies `"beter/exception-with-context": "dev-master"` and symlinks `/package-src` folder
   in the container with the `beter/exception-with-context` package. This requires to mount volume later, but gives 
   possibility to change source files right in the `src/` folder on the host machine and immediately see that changes inside running
   container.
6. Folder `test-app-src` is included to `.gitignore`, so it must not bother you.

> Note. Windows users must
> [allow creation of symlinks](https://docs.microsoft.com/en-us/windows/security/threat-protection/security-policy-settings/create-symbolic-links).

## Play

Open `http://localhost:8080/index.php` (or specify custom part as it was specified in `HOST_NGINX_PORT` env var).

## Tips

If you want to test specific version of the package you have 2 options:
* just edit `composer.json` before the docker build and replace `"beter/exception-with-context": "dev-master"` to specific version.
* after the build phase change `"beter/exception-with-context": "dev-master"` inside running container and run `composer update`.

## Development and testng pipeline

You may use `xdebug` to test the application,
check settings (`deploy/data/php/root/usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini`).

For cli debugging your IDE may require to run commands with `PHP_IDE_CONFIG` env variable. For example,

```
PHP_IDE_CONFIG="serverName=localhost" php script.php
```

`nginx` and `php` write their logs directly to `stderr` of the container. The project is about logging, so you definitely
need to read `stderr`. But yii's default configuration writes logs to files, so you may also check
`var/www/html/runtime/logs/app.log` file on the container.
