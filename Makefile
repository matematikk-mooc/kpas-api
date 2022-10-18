SHELL := /bin/bash
TAG=$(shell git rev-parse HEAD)

build_docker_TEST:
	docker build -f prod.Dockerfile -t udirkpas.azurecr.io/kpas-test-pipeline:$(TAG) .

build_and_push_docker_TEST: build_docker_TEST
	docker push udirkpas.azurecr.io/kpas-test-pipeline:$(TAG)
