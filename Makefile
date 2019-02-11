.PHONY:mysql pgsql sqlite router form views cover clean dbs seed migrate disable enable send dir config session flash app trans csrf hash

BASE=zen

UNIT="./vendor/bin/phpunit"
MYSQL_PASSWORD=root


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

all: vendor mysql pgsql sqlite router form views dir config session flash app trans csrf hash

mysql: seed
	@install -D $@.yaml config/db.yaml
	@$(UNIT) tests/$@
pgsql: seed
	@install -D $@.yaml config/db.yaml
	@$(UNIT) tests/$@
sqlite: seed
	@install -D $@.yaml config/db.yaml
	@$(UNIT) tests/$@
router:
	@$(UNIT) tests/$@
flash:
	@$(UNIT) tests/$@
form:
	@install -D mysql.yaml config/db.yaml
	@$(UNIT) tests/$@
views:
	@$(UNIT) tests/$@
dir:
	@$(UNIT) tests/$@
config:
	@$(UNIT) tests/$@
app:
	@$(UNIT) tests/$@
seed: dbs migrate
session:
	@$(UNIT) tests/$@
csrf:
	@$(UNIT) tests/$@
trans:
	@$(UNIT) tests/$@
hash:
	@$(UNIT) tests/$@
send: all
	git add .
	git commit -m "$(COMMIT)" -n
	git push origin --all
	git push origin --tags
disable:
	sudo install -D xdebug_d.ini /etc/php/conf.d/xdebug.ini
enable:
	sudo install -D xdebug.ini /etc/php/conf.d/xdebug.ini
help: ## Display the help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

vendor: ## Configure the app
	@composer install

migrate: migrate_mysql migrate_pgsql migrate_sqlite ## Run all migrations

serve: ## Start the development server
	@clear
	@php -S localhost:8000 -d display_errors=1 -t public
cover: ## Start a server to display the coverage
	@clear
	@php -S localhost:3000 -t COVERAGE

phinx: phinx.yml ## Create migration and seed
	 @vendor/bin/phinx create $(PHINX)table
	 @vendor/bin/phinx seed:create $(PHINX)Seeds

migrate_pgsql: ## Seed postgresql database
	@vendor/bin/phinx migrate -e pgsql
	@vendor/bin/phinx seed:run -e pgsql

migrate_mysql: ## Seed mysql database
	@vendor/bin/phinx migrate -e mysql
	@vendor/bin/phinx seed:run -e mysql

migrate_sqlite: ## Seed sqlite database
	@vendor/bin/phinx migrate -e sqlite
	@vendor/bin/phinx seed:run -e sqlite

dbs: clean ## Create all databases
	@psql -c "create database $(BASE);" -U postgres
	@mysql -uroot -p$(MYSQL_PASSWORD) -e "CREATE DATABASE $(BASE);"
	@touch $(BASE).sqlite3 && chmod 777 $(BASE).sqlite3
clean: ## Remove all databases
	psql  -c "DROP DATABASE IF EXISTS $(BASE);" -U postgres
	mysql -uroot -p$(MYSQL_PASSWORD) -e "DROP DATABASE IF EXISTS $(BASE);"
	$(RM) $(BASE).sqlite3
