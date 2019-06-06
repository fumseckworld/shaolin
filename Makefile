.PHONY: migrate seed router coverage

P=./vendor/bin/phinx
UNIT=./vendor/bin/phpunit
C=--coverage-html coverage

all: migrate router
migrate:
	$(P) $@ -e development

coverage:
	php -S localhost:8000 -t $@

router:
	$(UNIT) $(C)