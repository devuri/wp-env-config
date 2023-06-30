<?php

require_once \dirname( __FILE__, 2 ) . '/vendor/autoload.php';

// app test path
\define('APP_TEST_PATH', __DIR__ );
\define('ABSPATH', __DIR__ );

// true to run unit tests.
\define('WP_ENV_TEST_MODE', true);

// github actions environment variables.
\define('CORE_GITHUB_EVENT_NAME', getenv('GITHUB_EVENT_NAME'));
\define('CORE_GITHUB_REF', getenv('GITHUB_REF'));
\define('CORE_GITHUB_EVENT_PATH', getenv('GITHUB_EVENT_PATH'));
\define('CORE_GITHUB_HEAD_REF', getenv('GITHUB_HEAD_REF'));
\define('CORE_RUNNER_OS', getenv('RUNNER_OS'));
