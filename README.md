# plexus

[![Build Status](https://travis-ci.com/ankitjain28may/plexus.svg?token=PPiqU7v6mcqyZ9sMHrpV&branch=master)](https://travis-ci.com/ankitjain28may/plexus)
[![Coverage Status](https://coveralls.io/repos/github/ncs-jss/plexus/badge.svg?branch=master)](https://coveralls.io/github/ncs-jss/plexus?branch=master)

# Installation and Contribution

### Requirements :

1. PHP > 5.6
2. MySQL
3. Composer
4. Laravel > 5.3

### Installation :

Fork and clone this repo, or download it on your local system.

Open composer and run this given command:

```shell
composer install
composer update
```

After installing composer, rename the file `.env.example` to `.env`:

```shell
cp .env.example .env
```

Generate the application key:

```php
php artisan key:generate
```

Migrate the database:

```php
php artisan migrate
```

Seed the database:

```php
php artisan db:seed
```

Set db credentials in `.env` and run the project.

For login:
```
For Society login:
Username : nibble
Email Id : nibble@gmail.com
Password : helloworld
```

Run this project on localhost:

```php
php artisan serve
```

This project will run on this server:

```
http://localhost:8000/
```

## For development, Lint your code using PHP_CodeSniffer

1. Automatically resolve linting errors:

```php
vendor/bin/phpcbf --standard=ruleset.xml app
```
2. Checking for the linting errors:

```php
vendor/bin/phpcs --standard=ruleset.xml app
```
