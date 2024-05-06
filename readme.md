# KPAS (Kompetanseplattform Administrativt System)

LTI and REST API for KPAS.

## Deployment / Environments
We use [Azure Pipelines](https://dev.azure.com/UdirDIT/KPAS/_release?_a=releases&view=mine&definitionId=1) for CI/CD.

| Instance   | Deployment status                                                                                                                                                                                           | URL                                     | Branch |
|------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|-----------------------------------------|--------|
| Production | [![Build Status](https://vsrm.dev.azure.com/UdirDIT/_apis/public/Release/badge/0316919c-f4b4-4697-a30a-76c68c160969/3/6)](https://dev.azure.com/UdirDIT/KPAS/_release?_a=releases&view=mine&definitionId=3) | https://kpas.kompetanse.udir.no         | master |
| Staging    | [![Build Status](https://vsrm.dev.azure.com/UdirDIT/_apis/public/Release/badge/0316919c-f4b4-4697-a30a-76c68c160969/1/2)](https://dev.azure.com/UdirDIT/KPAS/_release?_a=releases&view=mine&definitionId=1) | https://kpas.staging.kompetanse.udir.no | stage  |

## API Documentation
API docs are located at `/docs`.

### Production
https://kpas.kompetanse.udir.no/docs

### Staging
https://kpas.staging.kompetanse.udir.no/docs

## Development


### Prerequisite
- docker
- docker compose
- ngrok

### The following things needs to be done prior to building:
- Add a .env file to the root directory of the repo.
- Add a new directory to the 'database' directory, where you add a config_platform.json file
- Genereate a api access key in Canvas.
- Generate a jwt keyset in the 'jwt_key_kpas' directory
- Use the public jwt key to generate a jwk.
- Open an ngrok tunnel
- Update the .env file and docker-compose file
- Create an LTI developer key in Canvas



### To run the application locally, run the following command:
```
docker compose -f dev.docker-compose.yaml up --build
```
This will start the application on port 8080.


### Migrate and populate the database:
To make sure the migrations are ran and populate the database, access the container:
```
docker exec -it kpas bash
```

Inside the container run this command to migrate:
```
php artisan migrate
```

To populate the database, run the following commands:
```
php artisan fetch_from:nsr
php artisan fetch_from:canvas
```

