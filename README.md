# LAMP Project with Docker

This project provides a lightweight local LAMP environment using Docker Compose. It includes:

- Apache + PHP 8.3
- MariaDB 12
- phpMyAdmin
- A sample PHP page that connects to the database

The main goal is to enable fast local development of PHP applications without needing to install Apache, PHP, and MySQL/MariaDB directly on the host machine.

> [!NOTE]
> This project was developed for learning purposes.

<p align="center">
  <a href="https://github.com/Akisolu/lamp-docker/actions/workflows/docker-syntax-checker.yml">
    <img src="https://github.com/Akisolu/lamp-docker/actions/workflows/docker-syntax-checker.yml/badge.svg" alt="Docker Build & Test" />
  </a>
  <br><br>
  <img src="https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white" alt="Docker" />
  <img src="https://img.shields.io/badge/Alpine_Linux-0D597F?style=for-the-badge&logo=alpine-linux&logoColor=white" alt="Alpine Linux" />
  <img src="https://img.shields.io/badge/Apache-D22128?style=for-the-badge&logo=apache&logoColor=white" alt="Apache" />
  <img src="https://img.shields.io/badge/PHP_8.3-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.3" />
  <img src="https://img.shields.io/badge/MariaDB_12-003545?style=for-the-badge&logo=mariadb&logoColor=white" alt="MariaDB 12" />
  <img src="https://img.shields.io/badge/phpMyAdmin-6C78AF?style=for-the-badge&logo=phpmyadmin&logoColor=white" alt="phpMyAdmin" />
  <img src="https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge" alt="License MIT" />
</p>

---

## Project structure

```text
.
├── .github/
│   └── workflows/
│       └── docker-syntax-checker.yml
├── public/
│   ├── index.php
│   └── info.php
├── .dockerignore (optional)
├── docker-compose.yml
├── Dockerfile
├── README.md
└── README.es.md
```

## Services

| Service | Image / Base | Port | Purpose |
| --- | --- | --- | --- |
| web | custom image built from the Dockerfile | 80:80 | Apache web server for PHP files |
| db | mariadb:12 | internal only | MariaDB database |
| phpmyadmin | phpmyadmin/phpmyadmin:5.2 | 8080:80 | Database administration interface |

## Technologies used

- PHP 8.3 with Apache 2
- Alpine Linux base image
- MariaDB database engine
- Docker Compose for orchestration

## Prerequisites

Before you begin, make sure you have:

- Docker installed
- Docker Compose installed
- Ports 80 and 8080 available on your machine

## Quick start

1. Clone or open the project folder:

```bash
git clone https://github.com/Akisolu/lamp-docker
cd lamp-docker
```

2. Start the containers:

```bash
docker compose up --build -d
```

3. Access the application:

- Web app: http://localhost
- phpMyAdmin: http://localhost:8080

## Default database configuration

The environment is configured with the following values:

- Database name: `mydatabase`
- Database user: `user`
- Database password: `password`
- Root password: `password`
- Host: `db` (internal Docker network)
- Port: `3306`

These values are defined in the `docker-compose.yml` file and are used by the sample PHP script in `public/index.php`.

## PHP example behavior

The `public/index.php` file attempts to connect to the MariaDB server using PDO and displays:

- `Successful connection to the database (MariaDB)!`
- or a database error message if the connection fails

This is useful for verifying that the web and database containers can communicate correctly.

## Useful commands

Start the stack:

```bash
docker compose up -d
```

Stop the stack:

```bash
docker compose down
```

View running containers:

```bash
docker compose ps
```

View logs:

```bash
docker compose logs -f
```

Rebuild the custom PHP image:

```bash
docker compose build
```

## Volumes and persistence

Database data is persisted using a Docker named volume:

```yaml
volumes:
  db-data:
```

This ensures that MariaDB data remains available even if the container is restarted or recreated.

## Notes

- The web root is mounted from `./public/` into Apache's document root.
- The custom Docker image installs the required PHP extensions, including support for MySQL/MariaDB.
- The Compose network `lamp-network` connects the web and database services internally.

## Troubleshooting

### The PHP app cannot connect to the database

Check whether the database container is running:

```bash
docker compose ps
```

Then inspect the logs:

```bash
docker compose logs db
```

Confirm that the database credentials in `docker-compose.yml` match those used by the application.

### Port already in use

If port 80 or 8080 is already occupied, change the host port mapping in `docker-compose.yml`:

```yaml
ports:
  - "8081:80"
```

Then use the new URL in your browser.
