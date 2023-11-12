# Parser

## Requirements
docker, docker-compose

## Installation

From sql_parser-php-1 container:

```bash
composer install \
&& cp .env.example .env \
&& php artisan key:generate \
&& chmod -R 777 storage/ \
&& npm install \
&& npm run build \
&& php artisan migrate \
&& php artisan horizon
```
