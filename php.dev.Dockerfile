FROM php:8.5-fpm-alpine AS php-runtime

COPY --from=mlocati/php-extension-installer:2.13.1 /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=composer:2.11.1 /usr/bin/composer /usr/local/bin/composer

RUN set -eux; \
    rm /usr/local/etc/php-fpm.conf \
       /usr/local/etc/php-fpm.conf.default \
    ; \
    rm -rf /usr/local/etc/php-fpm.d

RUN set -eux; \
    install-php-extensions \
      xdebug-3.5.3 \
    ;

COPY ./deploy/data/php/root/usr/local/etc/ /usr/local/etc/

RUN cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

FROM php-runtime AS development

COPY ./deploy/data/php/root/var/www/html/ /var/www/html/
COPY ./composer.json /package-src/composer.json
COPY ./src/ /package-src/src/

RUN composer install --no-interaction --prefer-dist
