<p align="center">
  <img src="https://user-images.githubusercontent.com/4777400/225331174-d5ae1c0e-5ec0-493b-aabc-91c4cc6a14c4.png" />
</p>




## WordPress Environment Configuration

[![Unit Tests](https://github.com/devuri/wp-env-config/actions/workflows/unit-tests.yml/badge.svg)](https://github.com/devuri/wp-env-config/actions/workflows/unit-tests.yml)

> **Note:** This branch is the development branch and contains features that may be in various stages of development **do not** use this branch in production, instead use the [release](https://github.com/devuri/wp-env-config/releases) versions.

### Introduction

`wp-env-config` is a small yet powerful package that simplifies the process of defining configuration constants in WordPress. By leveraging PHP dotenv, this package enables you to securely store sensitive configuration data in environment variables, which is a best practice for building and deploying software according to the twelve-factor app methodology.

### Installation

To use `wp-env-config`, you can install it via Composer. Run the following command in your terminal:

```shell
composer create-project devuri/wp-env-app .
```
> or for existing projects
```shell
composer require devuri/wp-env-config
```
Alternatively, you can add `devuri/wp-env-config` to your project's `composer.json` file:
```shell
"require": {
    "devuri/wp-env-config": "^0.30"
}
```
Once installed, you can begin using the package in your WordPress project.

```shell
# This is how the structure might look.

├── .env
├── wp-config.php
├── composer.json
├── composer.lock
├── LICENSE
├── public/
│   ├── index.php
│   ├── wp-admin/
│   ├── wp-content/
│   ├── wp-includes/
│   ├── .htaccess
│   ├── robots.txt
│   └── ...
└── vendor/

```

### Usage

To get started, create a `.env` file in the root directory of your project. 
In this file, define the environment variables you wish to use as configuration constants. For example:
> update the database credentials and other settings as needed.
```shell
WP_HOME='https://example.com'
WP_SITEURL="${WP_HOME}"

WP_ENVIRONMENT_TYPE='production'
DEVELOPER_ADMIN='0'

MEMORY_LIMIT='256M'
MAX_MEMORY_LIMIT='256M'

DB_NAME=wp_dbName
DB_USER=root
DB_PASSWORD=
DB_HOST=localhost
DB_PREFIX=wp_
```
Then, in your **wp-config.php** file, add the following code:
```php

<?php

use DevUri\Config\Setup;

require_once __FILE__ . '/vendor/autoload.php';

/**
 * The base configuration for WordPress
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */
Setup::init(__DIR__)->config(); // production

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = env('DB_PREFIX');


if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

// Sets up WordPress.
require_once ABSPATH . 'wp-settings.php';
    
```
This will load the environment variables from the `.env` file and define them as configuration constants.

```shell
# the following files are supported (in that order)

env
.env
.env.secure
.env.prod
.env.staging
.env.dev
.env.debug
.env.local

# By default, Dotenv will stop looking for files as soon as it finds one.

```

You can define as many constants as you need using this approach.

### CI/CD
We can use a GitHub Actions workflow to automate the deployment process. 

```yaml
name: remote ssh command
on: [push]
jobs:

  build:
    name: Build
    runs-on: ubuntu-latest
    steps:
    - name: executing remote ssh commands using password
      uses: appleboy/ssh-action@v0.1.10
      with:
        host: ${{ secrets.HOST }}
        username: ${{ secrets.USERNAME }}
        password: ${{ secrets.PASSWORD }}
        port: ${{ secrets.PORT }}
        script: whoami
```

https://github.com/marketplace/actions/ssh-remote-commands

### Why

The aim of this package is to simplify the definition of WordPress configuration constants by leveraging PHP dotenv to access environment variables stored in a .env file. By utilizing environment variables in this way, we can enhance the security of our WordPress installation by avoiding the storage of sensitive credentials in our code.

This approach adheres to the **twelve-factor app methodology** for building and deploying software, specifically principle three, which emphasizes the importance of storing configuration data in the environment. By implementing this package, we can follow this best practice and ensure that our WordPress instance is both secure and maintainable.
