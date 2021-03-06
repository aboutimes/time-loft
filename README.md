# timeloft
Blog system development based on laravel 5.5

## Demo

## Features

## Installation

### 1. Requirements and Environment
>PHP >= 7.0.0 <br />
>PHP OpenSSL Extension <br />
>PHP PDO Extension <br />
>PHP Mbstring Extension <br />
>PHP Tokenizer Extension <br />
>PHP XML Extension <br />
>Web server (apache/nginx/...) <br />
>Database (MySQL/PostgreSQL/SQLite/SQL Server) <br />
>Composer

### 2. Clone the source code

``git clone url``

### 3. Set the basic config

``cp .env.example .env``

``php artisan key:generate``

### 4. Composer install

``composer install -vvv``

### 5. Database stuff

Ajust the database information, then:

``php artisan migrate``

Seed the database if you want:

``php artisan db:seed``

### 6. create encryption keys

``php artisan passport:install``
