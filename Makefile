.PHONY: coverage

OLD=7.2.19
MEDIUM=7.3.6
LAST=7.3.6

TEST=./impero
C=--coverage-html coverage


all: prepare
	./vendor/bin/phpunit $(C)
coverage:
	php -S localhost:8000 -t $@
css:
	npx tailwind build core/Assets/css/app.css -o web/css/app.css

prepare: vendor
	mysql -uroot -proot -e 'DROP DATABASE IF EXISTS imperium;'
	mysql -uroot -proot -e 'CREATE DATABASE imperium;'
	vendor/bin/phinx migrate
	vendor/bin/phinx seed:run

vendor:
	composer install
