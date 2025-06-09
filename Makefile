.PHONY: $(MAKECMDGOALS)

.DEFAULT_GOAL := help
SHELL := /bin/bash

### Docker commands ###

build: ## Build containers
	docker compose build

rebuild: ## ReBuild containers
	docker compose build --no-cache

up: ## Up containers
	docker compose up -d

up-rebuild: ## Up containers
	docker compose up -d --force-recreate --build

up-alone: ## Up containers from current project and remove others
	docker compose up -d --remove-orphans

down: ## Down containers in current project
	docker compose down

restart: ## Restart containers
	docker compose restart

down-all: ## Down containers in current project and others
	docker compose down --remove-orphans

down-v: ## Down containers in current project with data in volumes
	docker compose down -v

connect-to-php-fpm: ## Run phpcs for ./src
	docker compose exec -i -t --privileged template-project-php-fpm bash

### Symfony commands ###

drop-database: ## Drop database
	docker compose exec -i -t --privileged template-project-php-fpm bin/console doctrine:schema:drop --force --full-database

migrations: ## Execute all migrations
	docker compose exec -i -t --privileged template-project-php-fpm bin/console doctrine:migrations:migrate -n

migration-diff: ## Create migration contains difference between db and current entities structure
	docker compose exec -i -t --privileged template-project-php-fpm bin/console doctrine:migrations:diff

cache-clear: ## Recreate cache
	rm -rf ./var/cache && docker compose exec -i -t --privileged template-project-php-fpm bin/console cache:clear --no-warmup

cache-warmup: ## Recreate cache
	rm -rf ./var/cache && docker compose exec -i -t --privileged template-project-php-fpm bin/console cache:warmup

clear-logs: ## Clear all logs
	truncate -s 0 ./var/log/*

### HELP commands ###

help: ## Show current help message
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' ./Makefile | sort | \
	awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}'