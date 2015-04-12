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
* Ruby
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

Create admin user (adjust you credentials):
```sh
vendor/bin/robo createadmin my-email@example.com password
```

Install gem:
```sh
gem install sass
```

Install selenium-standalone:
```sh
npm install --global selenium-standalone@latest
selenium-standalone install
```

Install frontend libraries:
```sh
npm install -g bower grunt-cli
bower install
```

Compile SASS:
```sh
grunt sass
```

## Development

### Tests

Start local Selenium server before running tests:
```sh
selenium-standalone start
```

Run tests using:
```sh
vendor/bin/phpunit
```

### Grunt tasks

* grunt sass - compile SASS files to CSS
* grunt watch - watch SASS files, and compile them to CSS on change
