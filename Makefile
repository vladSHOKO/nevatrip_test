start:
	docker compose up -d
stop:
	docker compose down
restart:
	docker compose down
	docker compose up -d
load-fixtures:
	docker compose run api ./bin/console doctrine:database:drop --if-exists --force
	docker compose run api ./bin/console doctrine:database:create
	docker compose run api ./bin/console doctrine:migrations:migrate --no-interaction
	docker compose run api ./bin/console doctrine:fixtures:load --no-interaction
composer-install:
	docker compose run api composer install
