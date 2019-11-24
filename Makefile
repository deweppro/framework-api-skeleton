.PHONY: install

PHP=php
COMPOSER=composer
DOCKER=docker-compose
JS_NPM=npm
SHELL=/bin/bash
DATE=`date '+%Y%m%d-%H%M%S'`
RUN_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
$(eval $(RUN_ARGS):;@:)

.PHONY: clean
clean:
	@rm -rf ./temp/*
	@rm -rf ./vendor/

.PHONY: docker
docker:
	@cd ./docker/ && ${DOCKER} build && ${DOCKER} up

.PHONY: update
update:
	@${COMPOSER} update --optimize-autoloader --prefer-dist --no-interaction --ignore-platform-reqs

.PHONY: install
install: clean
	@${COMPOSER} validate --no-check-all && ${COMPOSER} install --optimize-autoloader --prefer-dist --no-interaction --ignore-platform-reqs

.PHONY: test-back
test-back:
	@${PHP} vendor/bin/phpstan analyse -c phpstan.neon -l max app tests
	@${PHP} vendor/bin/phpunit --debug

.PHONY: test
test: test-back
