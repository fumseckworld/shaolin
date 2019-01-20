.PHONY:serve all serve add mysql pgsql sqlite dbs drop

BASE=zen
POSTGRESQL_PASSWORD=postgres
MYSQL_PASSWORD=root

CURRENT=7.3.1
NEXT=7.2.14
MIDDLE=7.1.26
OLD=7.0.33


UNIT="vendor/bin/phpunit"
SLEEP="sleep 2 && clear"
VERSION=$(shell php --version)
SEED="seed"

ifeq (phinx,$(firstword $(MAKECMDGOALS)))
  # use the rest as arguments for "run"
  PHINX := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  # ...and turn them into do-nothing targets
  $(eval $(PHINX):;@:)
endif

ifeq (send,$(firstword $(MAKECMDGOALS)))
  # use the rest as arguments for "run"
  COMMIT := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  # ...and turn them into do-nothing targets
  $(eval $(COMMIT):;@:)
endif


all: vendor dbs migrate
	@$(UNIT) --coverage-html COVERAGE


old:
	$(shell old)
$(OLD): $(SEED) old
	$(VERSION)
	$(SLEEP)
	$(UNIT)
$(MIDDLE): $(SEED)
	@bash php.sh $@
	$(VERSION)
	$(SLEEP)
	$(UNIT)
$(LATEST): $(SEED)
	@bash php.sh $@
	$(VERSION)
	$(SLEEP)
	$(UNIT)
$(NEXT): $(SEED)
	@bash php.sh $@
	$(VERSION)
	$(SLEEP)
	$(UNIT)
$(SEED): dbs migrate

disable:
	sudo install -D xdebug_d.ini /etc/php/conf.d/xdebug.ini
enable:
	sudo install -D xdebug.ini /etc/php/conf.d/xdebug.ini
help: ## Display the help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

vendor: ## Configure the app
	@composer install

migrate: pgsql mysql sqlite ## Run all migrations

serve: ## Start the development server
	@clear
	@php -S localhost:8000 -d display_errors=1 -t public

coverage: ## Start a server to display the coverage
	@clear
	@php -S localhost:3000 -t COVERAGE

phinx: phinx.yml ## Create migration and seed
	 @vendor/bin/phinx create $(PHINX)table
	 @vendor/bin/phinx seed:create $(PHINX)Seeds

pgsql: ## Seed postgresql database
	@vendor/bin/phinx migrate -e $@
	@vendor/bin/phinx seed:run -e $@

mysql: ## Seed mysql database
	@vendor/bin/phinx migrate -e $@
	@vendor/bin/phinx seed:run -e $@

sqlite: ## Seed sqlite database
	@vendor/bin/phinx migrate -e $@
	@vendor/bin/phinx seed:run -e $@

dbs: clean ## Create all databases
	@psql -c "create database $(BASE);" -U postgres
	@mysql -uroot -p$(MYSQL_PASSWORD) -e "CREATE DATABASE $(BASE);"
	@touch $(BASE).sqlite3 && chmod 777 $(BASE).sqlite3
clean: ## Remove all databases
	psql  -c "DROP DATABASE IF EXISTS $(BASE);" -U postgres
	mysql -uroot -p$(MYSQL_PASSWORD) -e "DROP DATABASE IF EXISTS $(BASE);"
	$(RM) $(BASE).sqlite3
