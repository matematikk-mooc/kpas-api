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
- [Docker Compose](https://docs.docker.com/compose/install/): A tool for defining and running multi-container Docker applications.
- [ngrok](https://ngrok.com/download/): A tool that enables you to expose a local development server to the internet, securely tunneling public URLs to your local machine.

## Configuration

### Docker Compose

1. Open up `docker-compose.yml` and fill out:
    - `CANVAS_ACCESS_KEY_NAME`
    - `CANVAS_ACCESS_KEY`
    - `APP_URL`

## Development

### Startup

- Start: `docker compose up`
    - Visit http://127.0.0.1:8080/api/filters

- Stop: `docker compose down`

### Populate database

You can use the CLI method to populate the database, which involves executing multiple API requests towards NSR and Canvas LMS. This can be inefficient and time-consuming, so if possible it's advisable to directly import a database dump from the stage environment.

#### Option A: CLI

1. Start up the application: `docker compose up`
1. Log into the docker container: `docker exec -it kpas_app bash`
2. Run database migrations: `php artisan migrate`
3. Populate the database:
    - Nasjonalt skoleregister: `php artisan fetch_from:nsr`
    - Canvas: `php artisan fetch_from:canvas`

#### Option B: Connecting to remote database

Alternatively, you can directly utilize the stage database by updating all variables prefixed with `DB_` in the `.env` file.

### Setup LTI tool in Canvas LMS

#### Register a new LTI tool

1. Login to Canvas LMS with an admin user
1. Click on Administrator->Unit Canvas->Utviklernøkler
1. Than click on the button that says + Utviklernøkler->LTI nøkkel
1. Under Konfigurer->Metode select the "Lim inn json" option
1. Then open the LTI template you want to add from `database/templates` like `rolle_grouper.json`
1. Copy & paste the contents into "LTI 1,3 Konfigurasjon" field
1. Fill out the other fields on the left side
    1. "Nøkkelnavn" and "Merknader" with the title value from the json contents
    1. "Eierens epost" with your email
1. Then click save and activate the key by clicking the toggle button in the list view

#### Add LTI tool to a course

1. From the "Utviklernøkler" page in admin copy the LTI client ID value under "Detaljer" (ex. 37270000000000266)
1. Paste your client ID into the `database/configs/config_platform.json` file
1. Go into the course where you want to add the LTI tool
1. Click on Innstillinger->Apper->Vis applikasjonskonfigurasjoner->+ App
1. Under the "Konfigurasjonstype" field select the "Av klient-ID" option
1. Paste the inn your client_id and save
1. Then find the LTI tool in the list click on the cog icon->Grupperings-ID
1. Copy & paste the value into your `config_platform.json` file under deployment array (ex. 1230:c2a0c788cf1e97c91dfa1d02933fb1994aa7e81c)
1. Refresh and the LTI should show up in the side menu, click on it and you are all setup

#### Add LTI tool globally

1. Same proccess as above but instead of going into a course
1. Click on Administrator->Unit Canvas->Innstillinger and add your LTI there

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
