PHP_CONTAINER := cqe_php_817

.PHONY: help
help: ## show make targets
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {sub("\\\\n",sprintf("\n%22c"," "), $$2);printf " \033[36m%-20s\033[0m  %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.PHONY: install
install: npm-install composer-install ## Install composer and npm dependencies

.PHONY: composer-install
composer-install: CMD=install

.PHONY: composer
composer composer-install:
	docker exec -w /var/www/html/ -t --user $(id -u):$(id -g) $(PHP_CONTAINER) \
 			php /var/www/html/composer.phar $(CMD)  --no-cache --ignore-platform-reqs --no-ansi

.PHONY: npm-install
npm-install: CMD=install

.PHONY: npm
npm npm-install:
	docker exec -w /var/www/html/ -t --user $(id -u):$(id -g) $(PHP_CONTAINER) \
 			npm $(CMD)

.PHONY: create-db
create-db: ## Create the database
	docker exec -w /var/www/html/ -t $(PHP_CONTAINER) php bin/console doctrine:database:drop --if-exists --force
	docker exec -w /var/www/html/ -t $(PHP_CONTAINER) php bin/console doctrine:database:create
	docker exec -w /var/www/html/ -t $(PHP_CONTAINER) php bin/console doctrine:schema:create

.PHONY: create-db-test
create-db-test:
	docker exec -w /var/www/html/ -t $(PHP_CONTAINER) php bin/console doctrine:database:drop --force  --env=test
	docker exec -w /var/www/html/ -t $(PHP_CONTAINER) php bin/console doctrine:database:create --env=test
	docker exec -w /var/www/html/ -t $(PHP_CONTAINER) php bin/console doctrine:schema:create --env=test

#.PHONY: fixtures
#fixtures:
#	docker exec -w /var/www/html/ $(PHP_CONTAINER) bin/console doctrine:fixtures:load --no-interaction --env=test

.PHONY: test
test: ## Run the tests
	docker exec -w /var/www/html/ -t --user $(id -u):$(id -g) $(PHP_CONTAINER) php /var/www/html/composer.phar check-style
	docker exec -w /var/www/html/ -t --user $(id -u):$(id -g) $(PHP_CONTAINER) php /var/www/html/composer.phar run-unit-tests

clean-cache: ## Clear the cache
	@rm -rf apps/*/*/var
	@docker exec -w /var/www/html/ -t --user $(id -u):$(id -g) $(PHP_CONTAINER) ./bin/console cache:warmup

start: ## Start the services
	@docker compose -f docker/docker-compose.yml up -d --build

stop: ## Stop the services
	@docker compose -f docker/docker-compose.yml down
