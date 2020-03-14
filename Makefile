.PHONY: tests

tests:
	@php -d memory_limit=4G  vendor/bin/phpstan analyse
