.PHONY: install update test

install:
	docker run --rm -it -v `pwd`:/project jeckel/composer --ignore-platform-reqs install

update:
	docker run --rm -it -v `pwd`:/project jeckel/composer --ignore-platform-reqs update

test:
	docker run -it --rm -v `pwd`:/project jeckel/phpunit
