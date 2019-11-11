# invoice-api

Este proyecto es la base del API para https://invoice.lito.com.es y https://invoice-demo.lito.com.es

## Requisitos

* Apache/nginx
* PHP >= 7.4
* MySQL >= 5.7

## Instalación

```bash
$> git clone https://github.com/eusonlito/invoice-api.git api
$> cd api
$> cp .env.example .env
$> vi .env # Rellenar el fichero
$> composer install
$> php artisan key:generate
$> php artisan jwt:key
$> php artisan migrate:refresh
$> php artisan db:seed --class=Database\\Seeds\\Database
$> php artisan db:seed --class=Database\\Fake\\Fake # Si deseas datos de prueba
$> php artisan cache:clear
```
