#!/usr/bin/env bash

cd docker && \
    cp .env.example .env && \
    docker-compose up --build -d && \
    docker-compose exec --user laradock workspace ./startup.sh
