# DURC
DURC is reverse CRUD

Builds Laravel Eloquent models and views by reading from the database assuming the DB follows some rules.

## Installation

Via Composer

```bash
$ composer require careset/durc
```

This project only works on Laravel 5.5 and above.

[package auto-discovery](https://medium.com/@taylorotwell/package-auto-discovery-in-laravel-5-5-ea9e3ab20518) should work..

Optional you can publish the configuration to provide an own service provider stub.

```bash
$ php artisan vendor:publish --provider="CareSet\DURC\DURCServiceProvider"
```

## Available commands

**Command:**
```bash
$ php artisan DURC:mine --DB=thefirst_db_name --DB=the_second_db --DB=the_third (etc...)
$ php artisan DURC:write
```

DURC:mine and DURC:write are two steps to allow for a auto-generated durc_conf.json file to be generated in config

This project requires datatables for bootstrap 4 to be available in the html
We use a webpack requirement for this, but you JS system might be different.

https://datatables.net/examples/styling/bootstrap4.html

