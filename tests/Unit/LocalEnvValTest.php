<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Urisoft\App\Setup;

/**
 * @internal
 *
 * @coversNothing
 */
class LocalEnvValTest extends TestCase
{
    protected $setup    = null;

    protected function setUp(): void
    {
        // parent::setUp();
        $this->setup = new Setup( APP_TEST_PATH );
    }

    public function test_wp_home(): void
    {
        $expectedValue = 'https://example.com';
        $actualValue = env('WP_HOME');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_wp_site_url(): void
    {
        $expectedValue = 'https://example.com/wp';
        $actualValue = env('WP_SITEURL');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_app_tenant_id(): void
    {
        $expectedValue = null;
        $actualValue = env('APP_TENANT_ID');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_is_multi_tenant_app(): void
    {
        $expectedValue = false;
        $actualValue = env('IS_MULTITENANT');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_basic_auth_user(): void
    {
        $expectedValue = 'admin';
        $actualValue = env('BASIC_AUTH_USER');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_basic_auth_password(): void
    {
        $expectedValue = 'demo';
        $actualValue = env('BASIC_AUTH_PASSWORD');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_use_app_theme(): void
    {
        $expectedValue = false;
        $actualValue = env('USE_APP_THEME');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_wp_environment_type(): void
    {
        $expectedValue = 'debug';
        $actualValue = env('WP_ENVIRONMENT_TYPE');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_backup_plugins(): void
    {
        $expectedValue = false;
        $actualValue = env('BACKUP_PLUGINS');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_send_email_change_email(): void
    {
        $expectedValue = false;
        $actualValue = env('SEND_EMAIL_CHANGE_EMAIL');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_sendgrid_api_key(): void
    {
        $expectedValue = '';
        $actualValue = env('SENDGRID_API_KEY');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_sudo_admin(): void
    {
        $actualValue = env('SUDO_ADMIN');

        $this->assertNull($actualValue);
    }

    public function test_wpenv_auto_login_secret_key(): void
    {
        $expectedValue = '2bf011c00c2d08b46d2a2a4d11eb7bd01f535f83f33ed254d7e5ddad67ac04a3';
        $actualValue = env('WPENV_AUTO_LOGIN_SECRET_KEY');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_web_app_public_key(): void
    {
        $expectedValue = 'b75b666f-ac11-4342-b001-d2546f1d3a5b';
        $actualValue = env('WEB_APP_PUBLIC_KEY');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_elementor_pro_license(): void
    {
        $expectedValue = '';
        $actualValue = env('ELEMENTOR_PRO_LICENSE');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_avada_key(): void
    {
        $expectedValue = '';
        $actualValue = env('AVADAKEY');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_memory_limit(): void
    {
        $expectedValue = '256M';
        $actualValue = env('MEMORY_LIMIT');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_max_memory_limit(): void
    {
        $expectedValue = '256M';
        $actualValue = env('MAX_MEMORY_LIMIT');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_force_ssl_admin(): void
    {
        $expectedValue = false;
        $actualValue = env('FORCE_SSL_ADMIN');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_force_ssl_login(): void
    {
        $expectedValue = false;
        $actualValue = env('FORCE_SSL_LOGIN');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_enable_s3_backup(): void
    {
        $expectedValue = false;
        $actualValue = env('ENABLE_S3_BACKUP');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_s3_encrypted_backup(): void
    {
        $expectedValue = false;
        $actualValue = env('S3ENCRYPTED_BACKUP');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_s3_backup_key(): void
    {
        $expectedValue = null;
        $actualValue = env('S3_BACKUP_KEY');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_s3_backup_secret(): void
    {
        $expectedValue = null;
        $actualValue = env('S3_BACKUP_SECRET');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_s3_backup_bucket(): void
    {
        $expectedValue = 'wp-s3snaps';
        $actualValue = env('S3_BACKUP_BUCKET');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_s3_backup_region(): void
    {
        $expectedValue = 'us-west-1';
        $actualValue = env('S3_BACKUP_REGION');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_s3_backup_dir(): void
    {
        $expectedValue = null;
        $actualValue = env('S3_BACKUP_DIR');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_delete_local_s3_backup(): void
    {
        $expectedValue = false;
        $actualValue = env('DELETE_LOCAL_S3BACKUP');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_db_name(): void
    {
        $expectedValue = 'local_wp_db';
        $actualValue = env('DB_NAME');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_db_user(): void
    {
        $expectedValue = 'root';
        $actualValue = env('DB_USER');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_db_password(): void
    {
        $expectedValue = 'password';
        $actualValue = env('DB_PASSWORD');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_db_host(): void
    {
        $expectedValue = 'localhost';
        $actualValue = env('DB_HOST');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_auth_key(): void
    {
        $expectedValue = ']6)=4CVbrGB}]D>A0@qy7wudtjT}*cx=KD@tpj+Pn)nZsdb<;8Zf5k6t-U*B$rA#';
        $actualValue = env('AUTH_KEY');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_secure_auth_key(): void
    {
        $expectedValue = ":pH+lj&BhxwIs1xIq_2J64C-*e3K|C1!JP/Mju@D<<*.chibS7;7ncp]r@(dD|Gr";
        $actualValue = env('SECURE_AUTH_KEY');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_logged_in_key(): void
    {
        $expectedValue = "IFTi<sI>g0R*(AHke!zQ%7=swR2iJ}i|M55/bnuA!(RBE)m&=tt#mKEn`&PHyrwg";
        $actualValue = env('LOGGED_IN_KEY');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_nonce_key(): void
    {
        $expectedValue = 'x(<yeYGhz4Uxop8B)IQn4?|SWmH>+>4xKycqI14-PA(x-re[.rYXe.|QrAadD+[z';
        $actualValue = env('NONCE_KEY');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_auth_salt(): void
    {
        $expectedValue = 'V2#iZwkzb7DQYLbR]Xgk6tjVg6Psp#$Gu$aSSxR4;,okWb>AHeU4qvWPN]WXAL]%';
        $actualValue = env('AUTH_SALT');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_secure_auth_salt(): void
    {
        $expectedValue = 'Wjvt)Z1(n6q$bAO|Y4IYEP)}{L5q?iR}pqWWHWSQrN,(pOu@-a|q%$FlRY6PwWr:';
        $actualValue = env('SECURE_AUTH_SALT');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_logged_in_salt(): void
    {
        $expectedValue = 'MY5`,b(![#.Ni_P))zF*pOK3n7[F5k!Yr`DoDPyh@2]p#yS3`)SQq@xNR;!2KtVL';
        $actualValue = env('LOGGED_IN_SALT');

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function test_nonce_salt(): void
    {
        $expectedValue = '2[]7?kLGY`e-,6B:EU,ul;w(:HJlo1v;>.5{pc)8vxknaVi|Q&luz|>pW3w*8lL0';
        $actualValue = env('NONCE_SALT');

        $this->assertEquals($expectedValue, $actualValue);
    }
}
