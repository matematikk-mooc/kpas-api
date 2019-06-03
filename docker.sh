#!/usr/bin/env bash

cd docker && \
    docker-compose up --build -d && \
    docker-compose exec --user laradock workspace ./startup.sh
