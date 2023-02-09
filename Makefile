PHP=php
CONSOLE=bin/console
BOWER=bower
COMPOSER=composer
WWW_USER=www-data
WWW_GROUP=www-data
DOCKER_DIRECTORY=docker
DOCKER_COMPOSE=docker-compose
DOCKER_SYNC=docker-sync-stack
PHP_UNIT=vendor/bin/phpunit
PHP_CAKE=bin/cake
PHP_CONTAINER=php_cake
GIT=git
VERSION=$(shell date '+1.0.0.%Y%m%d-%H%M%S')

release:
	$(GIT) tag -a '$(VERSION)' -m '$(VERSION)'

rc:
	$(GIT) tag -a '$(VERSION)rc' -m '$(VERSION)rc'

test:
	$(PHP) $(PHP_UNIT) --configuration phpunit.xml.dist

migrations:
	$(PHP_CAKE) migrations migrate

composer:
	$(COMPOSER) update  --no-progress --profile --prefer-dist

dup:
	cd $(DOCKER_DIRECTORY) && $(DOCKER_COMPOSE) build
	cd $(DOCKER_DIRECTORY) && $(DOCKER_COMPOSE) up -d

dstop:
	cd $(DOCKER_DIRECTORY) && $(DOCKER_COMPOSE) stop

dps:
	cd $(DOCKER_DIRECTORY) && $(DOCKER_COMPOSE) ps

drm:
	cd $(DOCKER_DIRECTORY) && $(DOCKER) rm $($(DOCKER) ps -aq)

drmi:
	cd $(DOCKER_DIRECTORY) && $(DOCKER) rmi $($(DOCKER) images -q)

dstart:
	cd $(DOCKER_DIRECTORY) && $(DOCKER_COMPOSE) build && $(DOCKER_SYNC) start

dphp:
	cd $(DOCKER_DIRECTORY) && $(DOCKER_COMPOSE) exec $(PHP_CONTAINER) bash

d: dstop dup dphp
