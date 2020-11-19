build:
	docker-compose build app

start:
	docker-compose up -d

install: build start install-env install-dep

install-env:
	docker-compose exec app cp .env.example .env

install-dep:
	docker-compose exec app composer install
	docker-compose exec app php artisan migrate && docker-compose exec app php artisan db:seed --class=AccountSeeder

test:
	docker-compose exec app ./vendor/bin/phpunit

track:
	docker-compose exec app php artisan track

track-account:
	$(eval $(call require-var,ACCOUNT_ID))
	docker-compose exec app php artisan track --account_id=${ACCOUNT_ID}
