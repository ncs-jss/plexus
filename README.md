# plexus

[![Build Status](https://travis-ci.org/ncs-jss/plexus.svg?branch=master)](https://travis-ci.org/ncs-jss/plexus)
[![Coverage Status](https://coveralls.io/repos/github/ncs-jss/plexus/badge.svg?branch=master)](https://coveralls.io/github/ncs-jss/plexus?branch=master)

# Installation and Contribution

### Requirements :

1. PHP > 5.6
2. MySQL
3. Composer
4. Laravel > 5.3

### Installation :

Fork and Clone this repo or download it on your local system.

Open composer and run this given command.

```shell
composer install
composer update
```

After installing composer, Rename the file `.env.example` to `.env`.

```shell
cp .env.example .env
```

Generate the Application key

```php
php artisan key:generate
```

Migrate the database.

```php
php artisan migrate
```

Seed the database

```php
php artisan db:seed
```

Set db credentials in `.env` and run the project.

For Login
```
For Society login:
Username : nibble
Email Id : nibble@gmail.com
Password : helloworld
```

Run this project on localhost

```php
php artisan serve
```

This project will run on this server:

```
http://localhost:8000/
```

## For development, Lint your code using PHP_CodeSniffer

1- Automatically resolve linting Errors -

```php
vendor/bin/phpcbf --standard=ruleset.xml app
```
2- Checking for the linting Errors -

```php
vendor/bin/phpcs --standard=ruleset.xml app
```
