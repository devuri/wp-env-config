<?php

namespace Urisoft\App;

use Exception;
use Symfony\Component\Filesystem\Filesystem;

class EnvGenerator
{
    protected $filesystem = null;

    public function __construct( Filesystem $filesystem )
    {
        $this->filesystem = $filesystem;

        // TODO create database over API and pass in credentials.
    }

    public function create( string $file_path, string $domain, ?string $prefix = null ): void
    {
        if ( ! $this->filesystem->exists( $file_path ) ) {
            $this->filesystem->dumpFile( $file_path, $this->env_file_content( $domain, $prefix ) );
        }
    }

    /**
     * Generate a cryptographically secure password.
     *
     * @param int  $length          The length of the password to generate.
     * @param bool $useSpecialChars Whether to include special characters in the password.
     *
     * @return string The generated password.
     *
     * @see https://github.com/devuri/secure-password
     */
    public static function rand_str( int $length = 8, $useSpecialChars = false )
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        if ( $useSpecialChars ) {
            $characters .= '!@#$%^&*()';
        }
        $charactersLength = \strlen( $characters );
        $password         = '';

        for ( $i = 0; $i < $length; $i++ ) {
            $password .= $characters[ random_int( 0, $charactersLength - 1 ) ];
        }

        return $password;
    }

    protected function env_file_content( ?string $wpdomain = null, ?string $prefix = null ): string
    {
        $auto_login_secret = bin2hex( random_bytes( 32 ) );
        $app_tenant_secret = bin2hex( random_bytes( 32 ) );
        $salt              = null;

        try {
            $salt = (object) $this->wpsalts();
        } catch ( Exception $e ) {
            wp_terminate( $e->getMessage() );
        }

        $home_url = "https://$wpdomain";
        $site_url = '${WP_HOME}/wp';
        if ( $prefix ) {
            $dbprefix = "wp_{$prefix}_";
        } else {
            $dbprefix = strtolower( 'wp_' . self::rand_str( 8 ) . '_' );
        }

        return <<<END
		WP_HOME='$home_url'
		WP_SITEURL="$site_url"

		WP_ENVIRONMENT_TYPE='debug'
		DISABLE_WP_APPLICATION_PASSWORDS=true
		SUDO_ADMIN='1'

		APP_TENANT_ID=null
		IS_MULTI_TENANT_APP=false

		BASIC_AUTH_USER='admin'
		BASIC_AUTH_PASSWORD='demo'

		# Email
		SEND_EMAIL_CHANGE_EMAIL=false
		SENDGRID_API_KEY=''

		# Premium
		ELEMENTOR_PRO_LICENSE=''
		ELEMENTOR_AUTO_ACTIVATION=true

		MEMORY_LIMIT='256M'
		MAX_MEMORY_LIMIT='256M'

		FORCE_SSL_ADMIN=false
		FORCE_SSL_LOGIN=false

		USE_APP_THEME=false
		BACKUP_PLUGINS=false

		# s3backup
		S3_BACKUP_KEY=null
		S3_BACKUP_SECRET=null
		S3_BACKUP_DIR=null
		ENABLE_S3_BACKUP=false
		S3ENCRYPTED_BACKUP=false
		S3_BACKUP_BUCKET='wp-s3snaps'
		S3_BACKUP_REGION='us-west-1'
		DELETE_LOCAL_S3BACKUP=false

		DB_NAME=local
		DB_USER=root
		DB_PASSWORD=password
		DB_HOST=localhost
		DB_PREFIX=$dbprefix

		AUTH_KEY='$salt->AUTH_KEY'
		SECURE_AUTH_KEY='$salt->SECURE_AUTH_KEY'
		LOGGED_IN_KEY='$salt->LOGGED_IN_KEY'
		NONCE_KEY='$salt->NONCE_KEY'
		AUTH_SALT='$salt->AUTH_SALT'
		SECURE_AUTH_SALT='$salt->SECURE_AUTH_SALT'
		LOGGED_IN_SALT='$salt->LOGGED_IN_SALT'
		NONCE_SALT='$salt->NONCE_SALT'

		END;
    }

    protected function wpsalts(): array
    {
        $saltsUrl     = 'https://api.wordpress.org/secret-key/1.1/salt/';
        $saltsContent = @file_get_contents( $saltsUrl );

        if ( false === $saltsContent ) {
            throw new Exception( 'Unable to retrieve salts from WordPress API.' );
        }

        $string  = str_replace( [ "\r", "\n" ], '', $saltsContent );
        $pattern = "/define\('([^']*)',\s*'([^']*)'\);/";
        $result  = [];

        if ( preg_match_all( $pattern, $string, $matches, PREG_SET_ORDER ) ) {
            foreach ( $matches as $match ) {
                $result[ $match[1] ] = $match[2];
            }
        } else {
            throw new Exception( 'Failed to parse the salts string.' );
        }

        return $result;
    }
}
