<?php

if (!defined('APP_HTTP_HOST')) {
    define('APP_HTTP_HOST', 'default_domain.com');
}

if (!defined('IS_MULTITENANT')) {
    define('IS_MULTITENANT', false);
}

if (!defined('WP_HOME')) {
    define('WP_HOME', 'https://example.com');
}

if (!defined('ASSET_URL')) {
    define('ASSET_URL', 'https://example.com/assets');
}

if (!defined('APP_PATH')) {
    define('APP_PATH', '/srv/users/dev/apps/example');
}

if (!defined('APP_CONTENT_DIR')) {
    define('APP_CONTENT_DIR', APP_PATH . '/content');
}

if (!defined('CONTENT_DIR')) {
    define('CONTENT_DIR', '/content');
}

if (!defined('WP_CONTENT_DIR')) {
    define('WP_CONTENT_DIR', 'public/content');
}

if (!defined('PUBLIC_WEB_DIR')) {
    define('PUBLIC_WEB_DIR', APP_PATH . '/public');
}

if (!defined('HTTP_ENV_CONFIG')) {
    define('HTTP_ENV_CONFIG', 'debug');
}

if (!defined('USE_MYSQL')) {
    define('USE_MYSQL', true);
}

if (!defined('COOKIEHASH')) {
    define('COOKIEHASH', 'c984d06aafbecf6bc55569f964148ea3');
}

if (!defined('APP_THEME_DIR')) {
    define('APP_THEME_DIR', APP_PATH . '/template');
}

if (!defined('CAN_DEACTIVATE_PLUGINS')) {
    define('CAN_DEACTIVATE_PLUGINS', true);
}

if (!defined('WPINC')) {
    define('WPINC', true);
}

if (!defined('APP_TEST_PATH')) {
    define('APP_TEST_PATH', __DIR__);
}

if (!defined('WEBAPP_ENCRYPTION_KEY')) {
    define('WEBAPP_ENCRYPTION_KEY', APP_TEST_PATH . '/.secret.txt');
}

if (!defined('SITE_CONFIG_DIR')) {
    define('SITE_CONFIG_DIR', APP_TEST_PATH . '/inc');
}

if (!defined('REQUIRE_TENANT_CONFIG')) {
    define('REQUIRE_TENANT_CONFIG', false );
}

if (!defined('APP_TENANT_ID')) {
    define('APP_TENANT_ID', false );
}

// WordPress specific constants
if (!defined('ABSPATH')) {
    define('ABSPATH', APP_TEST_PATH . '/wp');
}

if (!defined('MINUTE_IN_SECONDS')) {
    define('MINUTE_IN_SECONDS', 60);
}

if (!defined('HOUR_IN_SECONDS')) {
    define('HOUR_IN_SECONDS', 60 * MINUTE_IN_SECONDS);
}

if (!defined('DAY_IN_SECONDS')) {
    define('DAY_IN_SECONDS', 24 * HOUR_IN_SECONDS);
}

if (!defined('WEEK_IN_SECONDS')) {
    define('WEEK_IN_SECONDS', 7 * DAY_IN_SECONDS);
}

if (!defined('MONTH_IN_SECONDS')) {
    define('MONTH_IN_SECONDS', 30 * DAY_IN_SECONDS);
}

if (!defined('YEAR_IN_SECONDS')) {
    define('YEAR_IN_SECONDS', 365 * DAY_IN_SECONDS);
}
