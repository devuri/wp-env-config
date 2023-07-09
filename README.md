<p align="center">
  <img src="https://user-images.githubusercontent.com/4777400/225331174-d5ae1c0e-5ec0-493b-aabc-91c4cc6a14c4.png" />
</p>

## WordPress Environment Configuration

[![Unit Tests](https://github.com/devuri/wp-env-config/actions/workflows/unit-tests.yml/badge.svg)](https://github.com/devuri/wp-env-config/actions/workflows/unit-tests.yml)

### Introduction

`wp-env-config` is a small yet powerful package that simplifies the process of defining configuration constants in WordPress. By leveraging PHP dotenv, this package enables you to securely store sensitive configuration data in environment variables, which is a best practice for building and deploying software according to the twelve-factor app methodology.

> **Note** This repository houses the fundamental components of wp-env-config. If you are developing an application, please utilize wp-env-app located in this repository: [wp-env-app](https://github.com/devuri/wp-env-app).

### Installation

To use `wp-env-config`, you can install it via Composer. Run the following command in your terminal:

```shell
composer create-project devuri/wp-env-app blog
```
> or for existing projects
```shell
composer require devuri/wp-env-config
```
Alternatively, you can add `devuri/wp-env-config` to your project's `composer.json` file:
```shell
"require": {
    "devuri/wp-env-config": "^0.5"
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
In this file, define the environment variables you wish to use as configuration constants, update the database credentials and other settings as needed.

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

> Full list of [Environment Variables](https://devuri.github.io/wp-env-config/env/)


Then, in your **wp-config.php** file, add the following code:
```php

<?php

use Urisoft\App\Setup;

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

### Setup Options and Environment

```php

Setup::init(__DIR__)->config(); // production

```

```php
Setup::init(__DIR__)->config('development'); // development

```

```php
Setup::init(__DIR__)->config('staging'); // staging

```

```php
Setup::init(__DIR__)->config('production'); // production

```

```php
Setup::init(__DIR__)->config('secure'); // secure

```

```php
Setup::init(__DIR__)->config('development', false )->environment()->database()->salts()->apply();

```



```php
dump( Setup::init(__DIR__)->getEnvironment() ); // Get the current Environment setup.

```


### Environment Constants.

Debug must be on and 'development' set as WP_ENVIRONMENT_TYPE in the .env file.

```php
dump( Setup::init(__DIR__)->configMap() ); // Display a list of constants defined by Setup.
```

This will output the following:

```shell
"WP_ENVIRONMENT_TYPE" => "development"
"WP_DEBUG" => true
"SAVEQUERIES" => true
"WP_DEBUG_DISPLAY" => true
"WP_DISABLE_FATAL_ERROR_HANDLER" => true
"SCRIPT_DEBUG" => true
"WP_DEBUG_LOG" => true
"DB_NAME" => ""
"DB_USER" => ""
"DB_PASSWORD" => ""
"DB_HOST" => "localhost"
"DB_CHARSET" => "utf8mb4"
"DB_COLLATE" => ""
"WP_HOME" => ""
"ASSET_URL" => ""
"WP_SITEURL" => ""
"UPLOADS" => "wp-content/uploads"
"WP_MEMORY_LIMIT" => "256M"
"WP_MAX_MEMORY_LIMIT" => "256M"
"CONCATENATE_SCRIPTS" => true
"FORCE_SSL_ADMIN" => true
"FORCE_SSL_LOGIN" => true
"AUTOSAVE_INTERVAL" => 180
"WP_POST_REVISIONS" => 10
"AUTH_KEY" => ""
"SECURE_AUTH_KEY" => ""
"LOGGED_IN_KEY" => ""
"NONCE_KEY" => ""
"AUTH_SALT" => ""
"SECURE_AUTH_SALT" => ""
"LOGGED_IN_SALT" => ""
"NONCE_SALT" => ""
"DEVELOPERADMIN" => null
```

### Global helper functions.

> `asset()`

The ***asset()*** function will generate a URL for an asset.

* We can configure the asset URL by setting the `ASSET_URL` in your .env `ASSET_URL="${WP_HOME}/assets"`
* Or optionally in the main config file.

```php

asset( "/bootstrap/css/bootstrap-grid.css" ); // https://example.com/assets/dist/bootstrap/css/bootstrap-grid.css

asset( "/images/thing.png" ); // https://example.com/assets/dist/images/thing.png

asset( "/images/thing.png", "/static" ); // https://example.com/static/images/thing.png

```

> `asset_url()`

The ***asset_url()*** URL for the asset directory.

* **Note:** The `ASSET_URL` constant is optional.
* We can configure the asset URL by setting the `ASSET_URL` in your .env `ASSET_URL="${WP_HOME}/assets"`
* Or optionally in the main config file.


```php

asset_url(); // https://example.com/assets/dist/

asset_url() . "images/thing.png" // https://example.com/assets/dist/images/thing.png

asset_url( "/static" ); // https://example.com/static

```

> `env()`

The ***env()*** function can be used to get the value of an environment variable.

```php

env('FOO');

```


### Kernel.

> `Kernel` ***$args***

We can use the **Kernel** `$args` to setup a custom directory structure.

```php

$args = [
        'web_root'        => 'public',
        'wp_dir_path'     => 'wp',
        'asset_dir'       => 'assets',
        'content_dir'     => 'content',
        'plugin_dir'      => 'plugins',
        'mu_plugin_dir'   => 'mu-plugins',
        'disable_updates' => true,
    ];

$http_app = new Kernel(__DIR__, $args);

// or

$http_app = new Kernel(__DIR__, ['content_dir' => 'content']);

```

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

### Headless Mode
**Corcel**
Corcel is a collection of PHP classes built on top of Eloquent ORM (from Laravel framework), that provides a fluent interface to connect and get data directly from a WordPress database.
https://github.com/corcel/corcel

**Headless Mode**
A helper plugin for putting WordPress in "headless mode". Designed for when WordPress is the CMS for a headless/ decoupled WordPress site.
``` php
// Activate the plugin and In wp-config.php, add a line defining the constant:
define( 'HEADLESS_MODE_CLIENT_URL', 'https://example.com' );
```
https://github.com/Shelob9/headless-mode

### Why

The aim of this package is to simplify the definition of WordPress configuration constants by leveraging PHP dotenv to access environment variables stored in a .env file. By utilizing environment variables in this way, we can enhance the security of our WordPress installation by avoiding the storage of sensitive credentials in our code.

This approach adheres to the **twelve-factor app methodology** for building and deploying software, specifically principle three, which emphasizes the importance of storing configuration data in the environment. By implementing this package, we can follow this best practice and ensure that our WordPress instance is both secure and maintainable.
