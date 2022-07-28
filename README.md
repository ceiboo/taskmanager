# Crud de Tareas en PHP MVC
Challenge by José Luis Alfaro

### Instalación ###
* `git clone git@github.com:Ceiboo/taskmanager.git`
* `cd taskmanager`
* `docker-compose build php-mvc`
* `docker-compose up -d`
* `docker-compose exec php-mvc composer update`

### Configura tu Base de Datos ###
En DBeaver (u otro)
* `Host: 172.10.2.3`
* `Port: 3306`
* `Database: jla-mvc-task`
* `User: admin`
* `Password: pN34c0l0_22`

- Crea las tablas a partir del archivo:
* `docker-compose exec php-mvc php App/command Migrate`

### Configura tu ordenador ###
En tu archivo /etc/hosts añade
* `127.0.0.1 task.challenge.jla`

### Iniciar ###
- Ingresar a la aplicación:
* `http://task.challenge.jla/`
* `user: joseluis@ceiboo.com`
* `password: control`

### Otros comandos ###
* `docker-compose down`
