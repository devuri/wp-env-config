<?php

define( 'APP_HTTP_HOST', 'default_domain.com' );
define( 'IS_MULTITENANT', false );
define( 'WP_HOME', 'https://example.com');
define( 'ASSET_URL', 'https://example.com/assets');
define( 'APP_PATH', '/srv/users/dev/apps/example');
define( 'APP_CONTENT_DIR', APP_PATH . '/content');
define( 'CONTENT_DIR', '/content');
define( 'WP_CONTENT_DIR', 'public/content');
define( 'PUBLIC_WEB_DIR', APP_PATH . '/public');
define( 'HTTP_ENV_CONFIG', 'debug');
define( 'USE_MYSQL', true );
define( 'COOKIEHASH', 'c984d06aafbecf6bc55569f964148ea3' );
define( 'APP_THEME_DIR', APP_PATH . '/template' );
define( 'CAN_DEACTIVATE_PLUGINS', true );
define( 'WPINC', true );
define('APP_TEST_PATH', __DIR__ );
define( 'WEBAPP_ENCRYPTION_KEY', APP_TEST_PATH . '/.secret.txt' );

// WordPress
define( 'ABSPATH', APP_TEST_PATH . '/wp' );
define( 'MINUTE_IN_SECONDS', 60 );
define( 'HOUR_IN_SECONDS', 60 * MINUTE_IN_SECONDS );
define( 'DAY_IN_SECONDS', 24 * HOUR_IN_SECONDS );
define( 'WEEK_IN_SECONDS', 7 * DAY_IN_SECONDS );
define( 'MONTH_IN_SECONDS', 30 * DAY_IN_SECONDS );
define( 'YEAR_IN_SECONDS', 365 * DAY_IN_SECONDS );
