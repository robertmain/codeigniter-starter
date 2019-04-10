[![Build Status](https://travis-ci.org/robertmain/codeigniter-starter.svg?branch=master)](https://travis-ci.org/robertmain/codeigniter-starter)

# CodeIgniter Starter

[![Build Status](https://travis-ci.org/robertmain/codeigniter-starter.svg?branch=master)](https://travis-ci.org/robertmain/codeigniter-starter)

A CodeIgniter starter application designed to get up and running and to be able to write code that is as clean, and testable as possible.

## Installation

- With Composer: `composer create-project robertmain/codeigniter-starter --dev`
- Manually: `git clone https://github.com/robertmain/codeigniter-starter`

## Why

CodeIgniter applications are currently very difficult to test, since most rely on the huge God object `$this` that everything else (including the class loader ಠ_ಠ) decend from.

This project brings Composer into the mix, both to load third-party libraries from Packagist, and to facilitate the namespacing of models. This in-turn allows us to create abstractions of business objects that are more testable as a single unit of code and are easier to deal with in the application.

## What

This CodeIgniter starter project provides the following:

### Testing

A fully functional PHPUnit/Mockery setup with namespaced models

### A Base Model

A basic model abstraction providing some limited CRUD functionality and CRUD lifecycle callbacks. This was inspired by [Jamie Rumbelow\'s Base Model](https://github.com/jamierumbelow/codeigniter-base-model) but diverged in order to support soft deletes.

This base model also provides the metadata field names for `created_at`, `updated_at` and `deleted_at`. This is re-used in the abstract base migration to provide the names of the fields to create as datestamp fields.

### Templating

The `Plates` templating engine was broguht in to handle templating in CodeIgniter. This allowed for greater flexibility in frontend layout, particularly in regards to things like template reigions, inheritence and partial re-use etc.

### Namespacing

Models can now be namespaced in `App\Models`. The loader has been extended to support this, however it should behave normally. We get this behaviour for free by using Composer and PSR-4 namespacing in our models.

**Please note:**  due to the way that CodeIgniter loads controllers, controllers cannot be namespaced at this time, otherwise the framework will be unable to locate them.

### Environmental Configuration

Dotenv is now used to provide database settings. This prevents database configuration accidentally being comitted and provides an easy, consistent way to setup seperate environments

### Composer Scripts

Composer scritps have also been provided to help manage the development of the application. These are:

- `test:models` - Test the application models
- `test:controllers` - Test the application controllers
- `test` - Test models and controllers
- `docs` Generate php docs from the models and controllers
- `migrate:latest` - Run the latest database migration and upgrade the database to the latest version
- `migrate:flush` - Drop the entire database and re-create it. For safety reasons, the application will raise an exception if asked to drop it's database in a production environment

### Exceptions

This starter project also includes `crazycodr/standard-exceptions` which are used to provide a standardised set of PHP exceptions for common scenarios.
