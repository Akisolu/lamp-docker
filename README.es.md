# Proyecto LAMP con Docker

Este proyecto proporciona un entorno LAMP local y ligero utilizando Docker Compose. Incluye:

- Apache + PHP 8.3
- MariaDB 12
- phpMyAdmin
- Una página PHP de ejemplo que se conecta a la base de datos

El objetivo principal es permitir el desarrollo local rápido de aplicaciones PHP sin necesidad de instalar Apache, PHP y MySQL/MariaDB directamente en la máquina anfitriona.

>[!NOTE] 
> Este proyecto fue desarrollado con fines de aprendizaje

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

## Estructura del proyecto

```text
.
├── .github/
│   └── workflows/
│       ├── docker-release.yml
│       └── docker-syntax-checker.yml
├── public/
│   ├── index.php
│   └── info.php
├── .dockerignore
├── .env.example
├── .gitignore
├── docker-compose.yml
├── Dockerfile
├── README.md
└── README.es.md
```

## Servicios

| Servicio | Imagen / Base | Puerto | Propósito |
| --- | --- | --- | --- |
| web | imagen personalizada creada a partir del Dockerfile | 80:80 | Servidor web Apache para archivos PHP |
| db | mariadb:12 | solo interno | Base de datos MariaDB |
| phpmyadmin | phpmyadmin/phpmyadmin:5.2 | 8080:80 | Interfaz de administración de bases de datos |

## Tecnologías utilizadas

- PHP 8.3 con Apache 2
- Imagen base Alpine Linux
- Motor de base de datos MariaDB
- Docker Compose para la orquestación
- Configuración basada en variables de entorno con `.env.example`

## Requisitos previos

Antes de comenzar, asegúrate de tener:

- Docker instalado
- Docker Compose instalado
- Puertos 80 y 8080 disponibles en tu máquina

## Configuración de entorno

Este proyecto usa variables de entorno para configurar la aplicación y la base de datos.

1. Copia el archivo de ejemplo:

```bash
cp .env.example .env
```

2. Ajusta los valores en `.env`:

```env
ENV=
DB_ROOT_PASSWORD=
DB_USER=
DB_PASSWORD=
DB_DATABASE=
```

El archivo `docker-compose.yml` usa estos valores para MariaDB y para el contenedor web, mientras que la página PHP de ejemplo los lee en tiempo de ejecución desde el entorno.

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

La base de datos se configura mediante las variables de tu archivo `.env` (cópialas desde `.env.example`).
Utiliza los siguientes valores para el desarrollo local o sustitúyelos por los tuyos propios:

| Variable          | Valor        | Descripción                          |
| ----------------- | ------------ | ------------------------------------ |
| `DB_ROOT_PASSWORD`| `password`   | Contraseña de root de MariaDB        |
| `DB_USER`         | `lamp`       | Usuario de la aplicación             |
| `DB_PASSWORD`     | `lamp`       | Contraseña para `DB_USER`            |
| `DB_DATABASE`     | `mydatabase` | Nombre de la base de datos           |

> **Nota:** El host (`db`) y el puerto (`3306`) no se pueden configurar mediante este método.
> Vienen determinados por la red interna de Docker entre contenedores.

## Panel de estado

El archivo `public/index.php` es un panel visual de estado que verifica el funcionamiento de la pila tecnológica. Muestra dos tarjetas:

- **Servidor web**: versión de Apache, versión de PHP y directorio raíz del documento (*document root*), junto con un indicador de estado (en ejecución/en línea).
- **Base de datos**: estado de la conexión a MariaDB mediante PDO (motor, host, nombre de la base de datos), mostrando una etiqueta de *Conectado* o *Desconectado* y los detalles del error si la conexión falla.

Esto permite verificar de un vistazo que:

1. El contenedor de Apache está procesando PHP correctamente.
2. Los contenedores web y de base de datos pueden comunicarse a través de la red interna.

La página también incluye enlaces rápidos a phpMyAdmin y a `info.php` (la salida completa de `phpinfo()`).

## Comportamiento de servicios y health checks

Las actualizaciones recientes añadieron un chequeo de salud al contenedor de MariaDB y mejoraron las dependencias entre servicios:

- El servicio `web` espera a que la base de datos esté saludable antes de iniciar.
- El servicio `db` ejecuta una comprobación de disponibilidad con `healthcheck.sh`.
- El servicio `phpmyadmin` también espera a que `db` esté listo antes de arrancar.

Esto hace el inicio del stack más fiable en Docker Compose.

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
- El proyecto soporta configuración a través de variables de entorno para credenciales de base de datos y valores por defecto de phpMyAdmin.

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

Confirme que los valores en `.env` coincidan con las credenciales esperadas por la aplicación y por Docker Compose.

### Puerto ya en uso

Si el puerto 80 u 8080 ya está ocupado, cambie la asignación de puertos del host en `docker-compose.yml`:

```yaml
ports:
  - "8081:80"
```

Luego, utilice la nueva URL en su navegador.