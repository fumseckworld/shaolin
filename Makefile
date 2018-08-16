.PHONY:serve all serve add mysql pgsql sqlite dbs drop

BASE=imperiums

ifeq (phinx,$(firstword $(MAKECMDGOALS)))
  # use the rest as arguments for "run"
  PHINX := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  # ...and turn them into do-nothing targets
  $(eval $(PHINX):;@:)
endif

all: dbs pgsql mysql sqlite
	@clear
	@vendor/bin/phpunit --coverage-html coverage

conf: dbs pgsql mysql sqlite

serve: ## Start php server
	clear
	@php -S localhost:8000 -d display_errors=1

add: ## Add a library
	${COMPOSER} require $(COMPOSER_ARGS)

phinx: phinx.yml ## Create migration and seed
	 vendor/bin/phinx create $(PHINX)table
	 vendor/bin/phinx seed:create $(PHINX)Seeds
sql:
	./vendor/bin/phinx migrate -e $@
	./vendor/bin/phinx seed:run -e $@

pgsql:
	./vendor/bin/phinx migrate -e $@
	./vendor/bin/phinx seed:run -e $@
lpgsql:
	./vendor/bin/phinx migrate -e $@
	./vendor/bin/phinx seed:run -e $@

mysql:
	./vendor/bin/phinx migrate -e $@
	./vendor/bin/phinx seed:run -e $@

sqlite:
	./vendor/bin/phinx migrate -e $@
	./vendor/bin/phinx seed:run -e $@

dbs: drop
	psql -c "create database $(BASE);" -U postgres
	mysql -uroot -proot -e "CREATE DATABASE $(BASE);"
	touch $(BASE) && chmod 777 $(BASE)
drop:
	psql -c "DROP DATABASE IF EXISTS $(BASE);" -U postgres
	mysql -uroot -proot -e "DROP DATABASE IF EXISTS $(BASE);"
	$(RM) $(BASE)
