Work in progress. Not production ready.

## Build status
[![Build Status](https://travis-ci.org/cezarykluczynski/ComicCMS2.svg?branch=master)](https://travis-ci.org/cezarykluczynski/ComicCMS2)
[![Coverage Status](https://coveralls.io/repos/cezarykluczynski/ComicCMS2/badge.svg?branch=master)](https://coveralls.io/r/cezarykluczynski/ComicCMS2?branch=master)
[![Sauce Test Status](https://saucelabs.com/buildstatus/comiccms2)](https://saucelabs.com/u/comiccms2)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/cezarykluczynski/ComicCMS2/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/cezarykluczynski/ComicCMS2/?branch=master)

## About

This project is aimed at recreating some of the functionalities of
[ComicCMS](http://comiccms.com/). It does not share any common code,
nor does it offer any path of migration from the original.

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
vendor/bin/doctrine migrations:migrate -n
```

Create admin user (adjust you credentials):
```sh
vendor/bin/robo createadmin my-email@example.com password
```

Install gem for compiling SASS:
```sh
gem install sass
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

Install selenium-standalone:
```sh
npm install --global selenium-standalone@latest
selenium-standalone install
```

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
* grunt intern - run local JavaScript tests
