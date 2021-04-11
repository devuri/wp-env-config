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
