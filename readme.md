![](https://imgur.com/XWVOBSH.png "")

# API - Kompetanseplattform Administrativt System (KPAS)

This platform is operated by The Norwegian Directorate of Education's Department for Digital Services, which is responsible for managing a number of national digital solutions that support education and skills development.

KPAS provides competency packages across various themes to enhance skills and practices in kindergartens, schools, the Educational Psychological Service (PPT), training companies, and examination boards. The platform is designed to support collaborative and long-term professional development with embedded process and leadership support.

The application leverages LTI (Learning Tools Interoperability) standards for seamless integration with the Canvas LMS, enhancing the native functionalities of the learning management system. The KPAS also features a robust REST API that extends Canvas's capabilities by offering additional features and functionalities, which are utilized by both the LTI tools and the frontend project.

**Services**

| Service | Environment | URL |
|---------|-------------|-----|
| API | Production | https://kpas.kompetanse.udir.no/ |
| API | Stage | https://kpas.staging.kompetanse.udir.no/ |

**Related Codebases**

| Name | Description |
|------|-------------|
| [Frontend](https://github.com/matematikk-mooc/frontend/) | Custom frontend for Canvas LMS |
| [Statistics API](https://github.com/matematikk-mooc/statistics-api/) | Collects and serves statistics data for KPAS |

**Quick links**

- [Dependencies](#dependencies)
- [Configuration](#configuration)
- [Development](#development)
- [Deployment](#deployment)
- [Maintenance](#maintenance)

## Dependencies

- [Git](https://git-scm.com/): A free and open source distributed version control system designed to handle everything from small to very large projects with speed and efficiency.
- [Visual Studio Code](https://code.visualstudio.com/): A lightweight but powerful source code editor which runs on your desktop and is available for Windows, macOS and Linux.
- [Docker](https://docs.docker.com/get-docker/): A tool for developing, shipping, and running applications inside lightweight, portable containers.
- [Docker Compose](https://docs.docker.com/get-docker/): A tool for defining and running multi-container Docker applications.
- [ngrok](https://docs.docker.com/get-docker/): A tool that enables you to expose a local development server to the internet, securely tunneling public URLs to your local machine.

## Configuration

### Configure docker compose

1. Open up `docker-compose.yml` and fill out:
    - `APP_URL`
    - `CANVAS_ACCESS_KEY`

### Setup and populate database

It's advisable to directly import a database dump from the stage environment. Using the CLI method, which involves executing multiple API requests to populate the database, can be inefficient and time-consuming.

#### Connecting to remote database

Alternatively, you can directly utilize the stage database by updating all variables prefixed with `DB_` in the `.env` file.

#### CLI

1. Start up the application: `docker compose up --build`
1. Log into the docker container: `docker exec -it kpas_app bash`
2. Run database migrations: `php artisan migrate`
3. Populate the database:
    - Nasjonalt skoleregister: `php artisan fetch_from:nsr`
    - Canvas: `php artisan fetch_from:canvas`

### Setup LTI tool in Canvas LMS (TODO)

## Development

- Start: `docker compose up`
    - Visit http://127.0.0.1:8080/api/filters

- Stop: `docker compose down`

## Deployment (TODO)

### GitHub Actions

### Rollback


## Maintenance

Maintaining a Laravel project with a Vue.js frontend involves regular updates to ensure both the backend and frontend remain secure, performant, and aligned with the latest web standards. Here’s how updates are carried out across different components of the project:

### Composer Packages

1. **Update Packages**: Run `composer update` to fetch the latest versions of PHP packages.

1. **Security Audits**: Use tools like `composer security-checker` to perform security audits and identify potential vulnerabilities in PHP packages.

### NPM Packages

1. **Update Packages**: Regularly run `npm update` to fetch the latest versions of dependencies. Use `npm outdated` to check for available updates.

1. **Security Audits**: Perform security audits using `npm audit` to identify and resolve potential vulnerabilities.
