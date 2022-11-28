# KPAS (Kompetanseplattform Administrativt System)

LTI and REST API for KPAS.

## Production
We use [Azure Pipelines](https://dev.azure.com/UdirDIT/KPAS/_release?_a=releases&view=mine&definitionId=1) for CI/CD.

| Instance   | Deployment status                                                                                                                                                                                           | URL                                     | Branch |
|------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|-----------------------------------------|--------|
| Production | [![Build Status](https://vsrm.dev.azure.com/UdirDIT/_apis/public/Release/badge/0316919c-f4b4-4697-a30a-76c68c160969/3/6)](https://dev.azure.com/UdirDIT/KPAS/_release?_a=releases&view=mine&definitionId=3) | https://kpas.kompetanse.udir.no         | master |
| Staging    | [![Build Status](https://vsrm.dev.azure.com/UdirDIT/_apis/public/Release/badge/0316919c-f4b4-4697-a30a-76c68c160969/1/2)](https://dev.azure.com/UdirDIT/KPAS/_release?_a=releases&view=mine&definitionId=1) | https://kpas.staging.kompetanse.udir.no | stage  |

## Development
To run the application locally, run the following command:
```
docker compose -f dev.docker-compose.yaml up --build 
```
This will start the application on port 8080.
