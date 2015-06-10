Work in progress. Not production ready.

Wanna help? Check out [todo](docs/todo.md) and [roadmap](docs/roadmap.md).

## Build status
[![Build Status](https://travis-ci.org/cezarykluczynski/ComicCMS2.svg?branch=master)](https://travis-ci.org/cezarykluczynski/ComicCMS2)
[![Coverage Status](https://coveralls.io/repos/cezarykluczynski/ComicCMS2/badge.svg?branch=master)](https://coveralls.io/r/cezarykluczynski/ComicCMS2?branch=master)
[![Sauce Test Status](https://saucelabs.com/buildstatus/comiccms2)](https://saucelabs.com/u/comiccms2)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/cezarykluczynski/ComicCMS2/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/cezarykluczynski/ComicCMS2/?branch=master)

# Dependency status
Composer: [![Dependency Status](https://www.versioneye.com/user/projects/553563157f43bcd889000020/badge.svg?style=flat)](https://www.versioneye.com/user/projects/553563157f43bcd889000020)

Bower: [![Dependency Status](https://www.versioneye.com/user/projects/553563157f43bc60fe00002f/badge.svg?style=flat)](https://www.versioneye.com/user/projects/553563157f43bc60fe00002f)

npm: [![Dependency Status](https://www.versioneye.com/user/projects/553563107f43bc60fe000028/badge.svg?style=flat)](https://www.versioneye.com/user/projects/553563107f43bc60fe000028)

## About

This project is aimed at recreating some of the functionalities of
[ComicCMS](http://comiccms.com/). It does not share any common code,
nor does it offer any path of migration from the original at this point.

It started as a learning project for some new technologies I wanted to
familiarize myself with, and it could be abandonded at any time.
Use with caution, or actually don't use it at all, at least for now,
because it have almost no functionalities implemented.

## Revision control
This repository was originally started as a Mercurial repository,
for learning purposes. It can be used with Git and Mercurial alike.
It has both .gitignore and .hgignore files.

## Requirements
If you like to participate in the development process, here are the requirements:

* PHP 5.4+, with Composer
* PostgreSQL 9.1+
* Apache 2.2+
* Ruby 1.8.7+
* Node.js 0.12+

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

## Tests

### Functional tests
Functional test are written using Intern testing framework, and resides in <code>tests/</code> directory.

#### Installation
Functional tests require an admin account with a specific credentials. This account can be created by running:
```sh
vendor/bin/robo createadmin admin@example.com password
``

Selenium server is required for running functional tests locally.

Selenium can be installed by running:
```sh
npm install --global selenium-standalone@latest
selenium-standalone install
```

#### Running tests
To run functional tests, start local Selenium server before running tests:
```sh
selenium-standalone start
```

Run tests using:
```sh
grunt test
```

### Unit tests

Unit tests are written in PHPUnit.

#### Installation
PHPUnit is installed with other packages via Composer.

#### Running tests
Run tests using:
```sh
vendor/bin/phpunit
```

## Grunt tasks

* grunt sass - compile SASS files to CSS
* grunt watch - watch SASS files, and compile them to CSS on change
* grunt intern - run local JavaScript tests
