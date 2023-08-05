ENVIRONMENT_VARIABLES := $(shell env | grep ^APP_ | awk '{print "-e "$$1}')
SHELL=/bin/bash
PROJECT_NAME := $(shell pwd | xargs basename)
TEST_COMPOSE_FILES=-f docker-compose.yml -f docker-compose-test.yml

init: install run-backend

install:
	docker-compose run --rm --no-deps php bash -c "composer install"

run-backend:
	docker-compose up -d --remove-orphans

docker-rm:
	docker-compose down --remove-orphans --volumes

docker-stop:
	docker-compose down --remove-orphans

create-database:
	docker-compose run --rm php bash -c 'php bin/console doctrine:database:create'
	docker-compose run --rm php bash -c 'php bin/console doctrine:mig:mig'

drop-database:
	docker-compose run --rm php bash -c 'php bin/console doctrine:database:drop --force'

import-invoices:
	docker-compose run --rm php bash -c 'php bin/console import:invoices'

import-payments:
	docker-compose run --rm php bash -c 'php bin/console import:payments'

test: test-init-backend test-behat test-spec test-unit

test-init-backend:
	docker-compose ${TEST_COMPOSE_FILES} up -d
	docker-compose run --rm php bash -c 'APP_ENV=test php bin/console cache:pool:prune'
	docker-compose run --rm php bash -c 'APP_ENV=test php bin/console doctrine:migrations:migrate --no-interaction'

test-behat:
	docker-compose run --rm php bash -c 'php vendor/bin/behat --strict --no-interaction'

test-spec:
	docker-compose ${TEST_COMPOSE_FILES} run --rm --no-deps php sh -c 'vendor/bin/phpspec run'

test-unit:
	docker-compose ${TEST_COMPOSE_FILES} run --rm --no-deps php sh -c 'vendor/bin/phpunit -c phpunit.xml.dist --testsuite=Unit'

ssh-mysql:
	docker-compose exec mysql mysql -uroot -proot

php-cs-fix:
	docker-compose run --rm --no-deps php bash -c "tools/php-cs-fixer/vendor/bin/php-cs-fixer fix src"

php-cs:
	docker-compose run --rm --no-deps php bash -c "tools/php-cs-fixer/vendor/bin/php-cs-fixer src"

php-stan-analise:
	docker run --rm -v "$(PWD)":/app --entrypoint="/bin/sh" ghcr.io/phpstan/phpstan:latest-php8.2 -c "composer global require phpstan/phpstan-phpunit; composer global require  phpstan/phpstan-symfony; phpstan analyse"