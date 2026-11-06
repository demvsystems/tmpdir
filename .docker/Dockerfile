FROM php:8.5-fpm@sha256:baf3ea13a8207fa18e1011bad1016e8b5e59fc974c6966d0efa6f36a16f875f0

COPY . .
WORKDIR .

RUN bash -c "curl http://getcomposer.org/composer.phar --output /usr/local/bin/composer && chmod 755 /usr/local/bin/composer"
ENV COMPOSER_HOME=/usr/config/composer

RUN apt update
RUN apt -y install zip \
                zlib1g-dev \
                libzip-dev \
                git

RUN docker-php-ext-install zip
RUN apt -y autoremove

RUN composer install \
      --no-scripts \
      --no-autoloader \
      --ignore-platform-reqs

RUN usermod -u 1000 www-data && su -c "chown -R www-data:www-data /var/www/html/ $COMPOSER_HOME ."
USER www-data
