<?php

namespace Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class ConfigDefaultTest extends TestCase
{
    public function setUp(): void
    {
        sclean_sensitive_env(['SUDO_ADMIN', 'SENDGRID_API_KEY']);
    }

    public function test_app_config_default_returns_expected_structure_and_values(): void
    {
        $config = app_config_default();

        // Assert overall structure
        $this->assertIsArray($config);

        // Security checks
        $this->assertArrayHasKey('security', $config);
        $this->assertCount(5, $config['security']);
        $this->assertNull($config['security']['encryption_key']);
        $this->assertTrue($config['security']['brute-force']);
        $this->assertTrue($config['security']['two-factor']);
        $this->assertTrue($config['security']['no-pwned-passwords']);
        $this->assertEquals([], $config['security']['admin-ips']);

        // Mailer checks
        $this->assertArrayHasKey('mailer', $config);
        $this->assertCount(6, $config['mailer']);

        // Mailer services checks
        foreach (['brevo', 'postmark', 'sendgrid', 'mailerlite'] as $service) {
            $this->assertArrayHasKey($service, $config['mailer']);
            if ( isset($config['mailer'][$service]['apikey'])) {
                $this->assertNull($config['mailer'][$service]['apikey']);
            }
        }

        $this->assertArrayHasKey('mailgun', $config['mailer']);
        $this->assertNull($config['mailer']['mailgun']['domain']);
        $this->assertNull($config['mailer']['mailgun']['secret']);
        $this->assertEquals('api.mailgun.net', $config['mailer']['mailgun']['endpoint']);
        $this->assertEquals('https', $config['mailer']['mailgun']['scheme']);

        $this->assertArrayHasKey('ses', $config['mailer']);
        $this->assertNull($config['mailer']['ses']['key']);
        $this->assertNull($config['mailer']['ses']['secret']);
        $this->assertEquals('us-east-1', $config['mailer']['ses']['region']);

        // Sudo Admin checks
        $this->assertEquals(1, $config['sudo_admin']);
        $this->assertNull($config['sudo_admin_group']);

        // Web root and directories checks
        $this->assertEquals('public', $config['web_root']);
        $this->assertEquals('assets', $config['asset_dir']);
        $this->assertEquals('app', $config['content_dir']);
        $this->assertEquals('plugins', $config['plugin_dir']);
        $this->assertEquals('mu-plugins', $config['mu_plugin_dir']);
        $this->assertEquals('sqlitedb', $config['sqlite_dir']);
        $this->assertEquals('.sqlite-wpdatabase', $config['sqlite_file']);
        $this->assertEquals('brisko', $config['default_theme']);
        $this->assertEquals('templates', $config['theme_dir']);

        // Boolean checks
        $this->assertTrue($config['disable_updates']);
        $this->assertTrue($config['can_deactivate']);

        // Error handler check
        $this->assertNull($config['error_handler']);

        // S3 Uploads checks
        $this->assertArrayHasKey('s3uploads', $config);
        $s3uploads = $config['s3uploads'];
        $this->assertEquals('site-uploads', $s3uploads['bucket']);
        $this->assertEquals('', $s3uploads['key']);
        $this->assertEquals('', $s3uploads['secret']);
        $this->assertEquals('us-east-1', $s3uploads['region']);
        $this->assertEquals('https://example.com', $s3uploads['bucket-url']);
        $this->assertEquals('public', $s3uploads['object-acl']);
        $this->assertEquals('2 days', $s3uploads['expires']);
        $this->assertEquals(300, $s3uploads['http-cache']);

        // Redis checks
        $this->assertArrayHasKey('redis', $config);
        $redis = $config['redis'];
        $this->assertFalse($redis['disabled']);
        $this->assertEquals('127.0.0.1', $redis['host']);
        $this->assertEquals(6379, $redis['port']);
        $this->assertEquals('', $redis['password']);
        $this->assertFalse($redis['adminbar']);
        $this->assertFalse($redis['disable-metrics']);
        $this->assertFalse($redis['disable-banners']);
        $this->assertEquals('c984d06aafbecf6bc55569f964148ea3redis-cache', $redis['prefix']);
        $this->assertEquals(0, $redis['database']);
        $this->assertEquals(1, $redis['timeout']);
        $this->assertEquals(1, $redis['read-timeout']);

        // Public key checks
        $this->assertArrayHasKey('publickey', $config);
        $this->assertEquals('b75b666f-ac11-4342-b001-d2546f1d3a5b',$config['publickey']['app-key']);
        $this->assertEquals('pubkeys', $config['publickey_dir']);
    }
}
