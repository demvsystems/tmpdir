FROM php:8.5-fpm@sha256:10855b6da433b9cbc9975bbf142850910ee1cb977390669f5630d1bdde57664b

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
