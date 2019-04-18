.PHONY:mysql pgsql sqlite router form views cover clean dbs seed migrate disable enable send dir config session flash app trans csrf hash collection

BASE=zen

UNIT="./vendor/bin/phpunit"
MYSQL_PASSWORD=root
COVERAGE='--coverage-html '

ifeq (phinx,$(firstword $(MAKECMDGOALS)))
  # use the rest as arguments for "run"
  PHINX := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  # ...and turn them into do-nothing targets
  $(eval $(PHINX):;@:)
endif

ifeq (cover,$(firstword $(MAKECMDGOALS)))
  # use the rest as arguments for "run"
  DIR := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  # ...and turn them into do-nothing targets
  $(eval $(DIR):;@:)
endif

ifeq (send,$(firstword $(MAKECMDGOALS)))
  # use the rest as arguments for "run"
  COMMIT := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  # ...and turn them into do-nothing targets
  $(eval $(COMMIT):;@:)
endif

all: vendor mysql pgsql sqlite router form  dir config session flash app trans csrf hash collection

mysql: seed
	@install -D $@.yaml config/db.yaml
	@$(UNIT) tests/$@ $(COVERAGE) coverage/$@
pgsql: seed
	@install -D $@.yaml config/db.yaml
	@$(UNIT) tests/$@ $(COVERAGE) coverage/$@
sqlite: seed
	@install -D $@.yaml config/db.yaml
	@$(UNIT) tests/$@ $(COVERAGE) coverage/$@
router:
	@$(UNIT) tests/$@ $(COVERAGE) coverage/$@
flash:
	@$(UNIT) tests/$@ $(COVERAGE) coverage/$@
form:
	@install -D mysql.yaml config/db.yaml
	@$(UNIT) tests/$@ $(COVERAGE) coverage/$@
dir:
	@$(UNIT) tests/$@ $(COVERAGE) coverage/$@
config:
	@$(UNIT) tests/$@ $(COVERAGE) coverage/$@
app:
	@$(UNIT) tests/$@
seed: dbs migrate
session:
	@$(UNIT) tests/$@ $(COVERAGE) coverage/$@
csrf:
	@$(UNIT) tests/$@ $(COVERAGE) coverage/$@
trans:
	@$(UNIT) tests/$@ $(COVERAGE) coverage/$@
hash:
	@$(UNIT) tests/$@ $(COVERAGE) coverage/$@
collection:
	@install -D mysql.yaml config/db.yaml
	@$(UNIT) tests/$@ $(COVERAGE) coverage/$@
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
	@php -S localhost:3000 -t coverage/$(DIR)

phinx: ## Create migration and seed
	 @vendor/bin/phinx create $(PHINX)table
	 @vendor/bin/phinx seed:create $(PHINX)Seeds

migrate_pgsql: ## Seed postgresql database
	@vendor/bin/phinx migrate
	@vendor/bin/phinx seed:run

migrate_mysql: ## Seed mysql database
	@vendor/bin/phinx migrate
	@vendor/bin/phinx seed:run

migrate_sqlite: ## Seed sqlite database
	@vendor/bin/phinx migrate
	@vendor/bin/phinx seed:run

dbs: clean ## Create all databases
	@psql -c "create database $(BASE);" -U postgres
	@mysql -uroot -p$(MYSQL_PASSWORD) -e "CREATE DATABASE $(BASE);"
clean: ## Remove all databases
	psql  -c "DROP DATABASE IF EXISTS $(BASE);" -U postgres
	mysql -uroot -p$(MYSQL_PASSWORD) -e "DROP DATABASE IF EXISTS $(BASE);"
