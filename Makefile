.PHONY: install update test

install:
	@if [ $(shell docker volume ls | grep composer-home | wc -l) -eq 0 ] ; then docker volume create --name composer-home ; fi
	@docker run --rm -i -v `pwd`:/project -v composer-home:/composer jeckel/composer --ignore-platform-reqs install

update:
	@if [ $(shell docker volume ls | grep composer-home | wc -l) -eq 0 ] ; then docker volume create --name composer-home ; fi
	@docker run --rm -i -v `pwd`:/project -v composer-home:/composer jeckel/composer --ignore-platform-reqs update

test:
	@docker run -it --rm -v `pwd`:/project jeckel/phpunit
