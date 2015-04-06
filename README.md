Work in progress. Not production ready.

This project is aimed at recreating some of the functionalities of
[ComicCMS](http://comiccms.com/). It does not share any common code,
not does it offer any path of migration from the original.

It started as a learning project for some new technologies I wanted to
familiarize myself with, and it could be abandonded at any time.
Use with caution, or actually don't use it at all, at least for now,
because it have almost no functionalities implemented.

## Revision control
This repository was originally started as a Mercurial repository,
for learning purposes. It can be used with Git and Mercurial alike.
It has both .gitignore and .hgignore files.

## Requirements
If you like to participate in the development process,
here are the requirements:

* PHP 5.4+, with Composer
* PostgreSQL
* Apache
* Node

## Installation
Create PostgreSQL user and database:
```sh
createuser comiccms
createdb -O comiccms comiccms
```

Install packages via Composer:
```sh
composer install
```

Migrate database:
```sh
vendor/bin/phinx migrate -e development
```

Install frontend libraries:
```sh
npm install -g bower
bower install
```
