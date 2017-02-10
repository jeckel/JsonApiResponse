.PHONY: install update test

install:
	docker run --rm -i -v `pwd`:/project jeckel/composer --ignore-platform-reqs install

update:
	docker run --rm -i -v `pwd`:/project jeckel/composer --ignore-platform-reqs update

test:
	docker run -i --rm -v `pwd`:/project jeckel/phpunit
