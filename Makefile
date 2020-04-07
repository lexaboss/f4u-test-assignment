.PHONY: help get add remove update test

PHP=php
COLOR_WARNING = \033[31m
RUN_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
$(eval $(RUN_ARGS):;@:)

## Help
help:
	@printf "${COLOR_COMMENT}Usage:${COLOR_RESET}\n"
	@printf " make [target]\n\n"
	@printf "${COLOR_COMMENT}Available targets:${COLOR_RESET}\n"
	@awk '/^[a-zA-Z\-\_0-9\.@]+:/ { \
		helpMessage = match(lastLine, /^## (.*)/); \
		if (helpMessage) { \
			helpCommand = substr($$1, 0, index($$1, ":")); \
			helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
			printf " ${COLOR_INFO}%-16s${COLOR_RESET} %s\n", helpCommand, helpMessage; \
		} \
	} \
{ lastLine = $$0 }' $(MAKEFILE_LIST)

## Get shipping address by cient ID : `make get <client-id>`
get:
	$(eval CLIENT_ID=$(wordlist 1,1,$(RUN_ARGS)))
	@if [ -z "$(CLIENT_ID)" ]; then echo "${COLOR_WARNING}Provide client ID as a first argument.${COLOR_RESET}"; exit 1; fi
	@$(PHP) index.php get-address "${CLIENT_ID}";

## Add shipping address to client : `make get <client-id>`
add:
	$(eval CLIENT_ID=$(wordlist 1,1,$(RUN_ARGS)))
	@if [ -z "$(CLIENT_ID)" ]; then echo "${COLOR_WARNING}Provide client ID as a first argument.${COLOR_RESET}"; exit 1; fi
	@$(PHP) index.php add-address "${CLIENT_ID}";

## Remove shipping address from client : `make remove <client-id>`
remove:
	$(eval CLIENT_ID=$(wordlist 1,1,$(RUN_ARGS)))
	@if [ -z "$(CLIENT_ID)" ]; then echo "${COLOR_WARNING}Provide client ID as a first argument.${COLOR_RESET}"; exit 1; fi
	@$(PHP) index.php remove-address "${CLIENT_ID}";

## Update shipping address: `make remove <client-id>`
update:
	$(eval CLIENT_ID=$(wordlist 1,1,$(RUN_ARGS)))
	@if [ -z "$(CLIENT_ID)" ]; then echo "${COLOR_WARNING}Provide client ID as a first argument.${COLOR_RESET}"; exit 1; fi
	@$(PHP) index.php update-address "${CLIENT_ID}";

## Running PHPUnit tests
test:
	@$(PHP) ./vendor/phpunit/phpunit/phpunit --no-configuration tests
