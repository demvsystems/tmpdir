.PHONY: new kill composer-install composer-up up test

new: kill
	docker-compose up -d --build --remove-orphans
	make composer-install

composer-install:
	docker-compose exec php-fpm composer install --optimize-autoloader

composer-up:
	docker-compose exec php-fpm composer up

up:
	docker-compose up -d

kill:
	docker-compose kill
	docker-compose down --volumes --remove-orphans

test:
	docker-compose exec php-fpm composer test
