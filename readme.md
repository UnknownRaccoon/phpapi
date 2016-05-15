# Basic Photoservice API
A small RESTful API powered by Laravel
## Installation requirements
To run the application, you'll need:
- Web Server
- PHP >= 5.5.9
- mysql (or mariadb)
- PDO PHP Extension
- Composer
- memcached & memcached PHP Extension
- php-gd PHP Extension
- cron implementation

## Installation
First, install all required dependencies with composer:
```sh
$ composer install
```
Then, create an empty database or import the one from the project root. You can set database connection parameters and other settings in ".env" file in project root.
If you decided to create an empty DB, you have to run:
```sh
$ php artisan migrate
```
To make cron run scheduled tasks, execute:
```sh
$ crontab -e
```
and add the following line there:
```sh
* * * * * php </path/to/artisan> schedule:run >> /dev/null 2>&1
```