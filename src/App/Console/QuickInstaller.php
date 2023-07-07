<?php

namespace Urisoft\App\Console;

use Urisoft\App\Console\Traits\Env;
use Urisoft\App\Console\Traits\Generate;

class QuickInstaller
{
    use Env;
    use Generate;

    protected $wp_dir_path = null;
    protected $params      = null;

    /**
     * Setup Installer.
     *
     * @param string $wp_dir_path The base app path, e.g., __DIR__.
     * @param array  $params      .
     */
    public function __construct( string $wp_dir_path, array $params = [] )
    {
        $this->wp_dir_path = $wp_dir_path;

        $this->params = array_merge(
            [
                'blog_title'    => 'Web Application:' . mt_rand( 10, 99 ),
                'user_name'     => 'admin',
                'user_email'    => 'admin@example.com',
                'is_public'     => true,
                'deprecated'    => '',
                'user_password' => $this->rand_str(),
                'language'      => '',
            ],
            $params
        );
    }

    public function get_params(): array
    {
        return $this->params;
    }

    /**
     * Installs WordPress and checks for the existence of default tables.
     *
     * @return null|(int|string)[] Returns the result of the wp_install() function if the installation is successful and the tables don't exist, or null if any table already exists.
     *
     * @psalm-return array{url: string, user_id: int, password: string, password_message: string}|null
     */
    public function install(): ?array
    {
        \define( 'WP_INSTALLING', true );

        /** Load WordPress Bootstrap */
        require_once $this->wp_dir_path . '/wp-load.php';

        /** Load WordPress Administration Upgrade API */
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        /** Load WordPress Translation Install API */
        require_once ABSPATH . 'wp-admin/includes/translation-install.php';

        /** Load wpdb */
        require_once ABSPATH . WPINC . '/class-wpdb.php';

        nocache_headers();

        global $wpdb;

        if ( is_blog_installed() ) {
            return null;
        }

        if ( false === self::tables_exist( $wpdb ) ) {
            $installed = wp_install(
                $this->params['blog_title'],
                $this->params['user_name'],
                $this->params['user_email'],
                $this->params['is_public'],
                '',
                $this->params['user_password'],
                $this->params['language']
            );

            $this->params = array_merge( $installed, $this->params );

            return $installed;
        }

        return null;
    }

    protected static function tables_exist( $wpdb ): bool
    {
        $tables = [
            'posts',
            'comments',
            'users',
            'terms',
            'term_taxonomy',
            'term_relationships',
            'options',
        ];

        $tables_exist = false;

        foreach ( $tables as $table ) {
            $table_name   = $wpdb->prefix . $table;
            $table_exists = $wpdb->get_var(
                $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name )
            );

            if ( $table_exists ) {
                $tables_exist = true;

                break;
            }
        }

        return $tables_exist;
    }
}
