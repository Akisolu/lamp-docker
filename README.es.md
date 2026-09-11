# Proyecto LAMP con Docker

Este proyecto proporciona un entorno LAMP local y ligero utilizando Docker Compose. Incluye:

- Apache + PHP 8.3
- MariaDB 12
- phpMyAdmin
- Una página PHP de ejemplo que se conecta a la base de datos

El objetivo principal es permitir el desarrollo local rápido de aplicaciones PHP sin necesidad de instalar Apache, PHP y MySQL/MariaDB directamente en la máquina anfitriona.

>[!NOTE]> Este proyecto fue desarrollado con fines de aprendizaje

<p align="center">
  <img src="https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white" alt="Docker" />
  <img src="https://img.shields.io/badge/Alpine_Linux-0D597F?style=for-the-badge&logo=alpine-linux&logoColor=white" alt="Alpine Linux" />
  <img src="https://img.shields.io/badge/Apache-D22128?style=for-the-badge&logo=apache&logoColor=white" alt="Apache" />
  <img src="https://img.shields.io/badge/PHP_8.3-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.3" />
  <img src="https://img.shields.io/badge/MariaDB_12-003545?style=for-the-badge&logo=mariadb&logoColor=white" alt="MariaDB 12" />
  <img src="https://img.shields.io/badge/phpMyAdmin-6C78AF?style=for-the-badge&logo=phpmyadmin&logoColor=white" alt="phpMyAdmin" />
  <img src="https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge" alt="License MIT" />
</p>

---

## Estructura del proyecto

```text
.
├── public/
│ ├── index.php
│ └── info.php
|── .dockerignore (opcional)
├── docker-compose.yml
├── Dockerfile
└── README.md
```

## Servicios

| Servicio | Imagen / Base | Puerto | Propósito |
| --- | --- | --- | --- |
| web | imagen personalizada creada a partir del Dockerfile | 80:80 | Servidor web Apache para archivos PHP |
| db | mariadb:12 | solo interno | Base de datos MariaDB |
| phpmyadmin | phpmyadmin/phpmyadmin:5.2 | 8080:80 | Interfaz de administración de bases de datos | ## Tecnologías utilizadas

- PHP 8.3 con Apache 2
- Imagen base Alpine Linux
- Motor de base de datos MariaDB
- Docker Compose para la orquestación

## Requisitos previos

Antes de comenzar, asegúrate de tener:

- Docker instalado
- Docker Compose instalado
- Puertos 80 y 8080 disponibles en tu máquina

## Inicio rápido

1. Clona o abre la carpeta del proyecto:

```bash
git clone https://github.com/Akisolu/lamp-docker
cd lamp-docker
```

2. Inicia los contenedores:

```bash
docker compose up --build -d
```

3. Accede a la aplicación:

- Aplicación web: http://localhost
- phpMyAdmin: http://localhost:8080

## Configuración predeterminada de la base de datos

El entorno está configurado con los siguientes valores:

- Nombre de la base de datos: `mydatabase`
- Usuario de la base de datos: `user`
- Contraseña de la base de datos: `password`
- Contraseña de root: `password`
- Host: `db` (red interna de Docker)
- Puerto: `3306`

Estos valores están definidos en el archivo `docker-compose.yml` y son utilizados por el script PHP de ejemplo en `public/index.php`.

## Comportamiento del ejemplo en PHP

El archivo `public/index.php` intenta conectarse al servidor MariaDB utilizando PDO y muestra:

- `Successful connection to the database (MariaDB)!`
- o un mensaje de error de base de datos si la conexión falla

Esto es útil para verificar que los contenedores web y de base de datos pueden comunicarse correctamente.

## Comandos útiles

Iniciar el stack:

```bash
docker compose up -d
```

Detener el stack:

```bash
docker compose down
```

Ver contenedores en ejecución:

```bash
docker compose ps
```

Ver registros (logs):

```bash
docker compose logs -f
```

Reconstruir la imagen personalizada de PHP:

```bash
docker compose build
```

## Volúmenes y persistencia

Los datos de la base de datos se persisten utilizando un volumen con nombre de Docker:

```yaml
volumes:
    db-data:
```

Esto garantiza que los datos de MariaDB permanezcan disponibles incluso si el contenedor se reinicia o se recrea.

## Notas

- La raíz web se monta desde `./public/` en el directorio raíz de documentos de Apache. 
- La imagen de Docker personalizada instala las extensiones de PHP necesarias, incluyendo el soporte para MySQL/MariaDB.
- La red de Compose `lamp-network` conecta internamente los servicios web y de base de datos.

## Solución de problemas

### La aplicación PHP no puede conectarse a la base de datos

Verifique si el contenedor de la base de datos se está ejecutando:

```bash
docker compose ps
```

A continuación, revise los registros (logs):

```bash
docker compose logs db
```

Confirme que las credenciales de la base de datos en `docker-compose.yml` coincidan con las utilizadas por la aplicación.

### Puerto ya en uso

Si el puerto 80 u 8080 ya está ocupado, cambie la asignación de puertos del host en `docker-compose.yml`:

```yaml
ports:
- "8081:80"
```

Luego, utilice la nueva URL en su navegador.