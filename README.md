## wp env config

A small package to define WordPress configuration constants using environment variables with a .env file.

This package will allow define WordPress configuration constants using [PHP dotenv](https://github.com/vlucas/phpdotenv). 
We will be securing WordPress using environment variables from .env as an added layer of security. 
It’s good practice to never store sensitive credentials in your code.

This follows the twelve-factor app methodology for building and deploying software.
Number three states that we should Store config in the environment.


## Installation

Installation is easy using [Composer](https://getcomposer.org/):

```bash
$ composer require devuri/wp-env-config
```
## Usage

The `.env` file is generally kept out of version control since it can contain
sensitive API keys and passwords. The `.env` file should be added to the project's `.gitignore` file 
so that it will never be committed.
This ensures that no sensitive data will ever be in the version control history so there is less risk
of a security breach.


Add your wp-config settings to a `.env` file in the root of your
project. **Make sure the `.env` file is added to your `.gitignore` so it is not
checked-in the code**, the `wp-config` file should be in the same dir.
WordPress will automatically look one directory above your WordPress installation for the `wp-config.php` file, 
You can safely move it one directory above your WordPress installation.

Database credentials:

```shell
DB_NAME=
DB_USER=root
DB_PASSWORD=
DB_HOST=127.0.0.1
```

Salts and Keys:

```shell
AUTH_KEY=
SECURE_AUTH_KEY=
LOGGED_IN_KEY=
NONCE_KEY=
AUTH_SALT=
SECURE_AUTH_SALT=
LOGGED_IN_SALT=
NONCE_SALT=
```
Take a look at this sample `env` file [.env.example](https://github.com/devuri/wp-env-config/blob/master/.env-example)

You can get **Env Format WordPress Salts** from Roots.io Generator https://roots.io/salts.html


You can then load `.env` in your `wp-config.php` with:

```php
require_once  dirname( __FILE__ ) . '/vendor/autoload.php';

use DevUri\Config\Setup;

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
$table_prefix = "wp_";

```
This should be above the comment `/* That's all, stop editing! Happy publishing. */`
Thats it, you're done!

## Advanced Usage

This package will ignore values that are declared before the `Setup::init(__DIR__)->config()` is initialized.
To override setup constant defined in `config` method define them before  `Setup::init(__DIR__)->config()`.

In this example  `config` will ignore `UPLOADS` constant since its already defined:
```php

// Custom Uploads Directory.
define('UPLOADS', 'app/uploads' );

Setup::init(__DIR__)->config();

```
or simply use `Setup::get( 'UPLOAD_DIR' )`:

```php

// Custom Uploads Directory.
define('UPLOADS', Setup::get( 'UPLOAD_DIR' ) );

```
After setup we can define other constant in the normal way or using `env` function, remember to include with use `use function Env\env;` when using the `env` function:

```php
use function Env\env;

define('UPLOADS', env( 'UPLOAD_DIR' ) );

```
Both `Setup::get( 'UPLOAD_DIR' )` and `env( 'UPLOAD_DIR' )` will grab the value from .env file.



**Hardening and secure setup mode.**


```php
// secure setup mode:
Setup::init(__DIR__)->config('secure');

```
This will disable both file editor and installer for themes and plugins.

> **note** you will need to update plugins and theme manually or manage updates with composer.


## Only use env for Salts and Database config.


You can tell setup to only use env file for **database** and **salts** by setting the second param as false:

```php
// setup for database and salts only.
Setup::init(__DIR__)->config('development', false )->environment()->database()->salts()->apply();

```


## Setup Options and Environment

list of setup options

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


## List of Environment Constants.

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
## License
wp env config is licensed under The [MIT License](https://github.com/devuri/wp-env-config/blob/master/LICENSE).
