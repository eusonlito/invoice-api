# invoice-api

Este proyecto es la base del API para https://invoice.lito.com.es y https://invoice-demo.lito.com.es

## Requisitos

* Apache/nginx
* PHP >= 7.4
* MySQL >= 5.7

```bash
$> sudo apt-get install python-pip swig
$> sudo pip install endesive
```


## Instalación

```bash
$> git clone https://github.com/eusonlito/invoice-api.git api
$> cd api
$> cp .env.example .env
$> vi .env # Rellenar con la configuración de entorno
$> composer install
$> php artisan key:generate
$> php artisan jwt:key
$> composer install # Necesario para limpiar y regenerar las cachés después de realizar cambios en el .env
$> php artisan migrate:refresh
$> php artisan db:seed --class=Database\\Seeds\\Database
$> php artisan db:seed --class=Database\\Fake\\Fake # Si deseas datos de prueba
$> php artisan cache:clear
```
