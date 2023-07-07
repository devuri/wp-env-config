<?php

namespace Tests\Unit\App\Console\Traits;

use PHPUnit\Framework\TestCase;
use Urisoft\App\Console\Traits\Generate;

/**
 * @internal
 *
 * @coversNothing
 */
class GenerateTraitTest extends TestCase
{
    use Generate;

    public function test_uuid(): void
    {
        $uuid = self::uuid();

        $this->assertNotEmpty($uuid);
        $this->assertIsString($uuid);
    }

    public function test_rand_str(): void
    {
        $length = 8;
        $randStr = self::rand_str($length);

        $this->assertEquals($length, \strlen($randStr));
        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9][a-zA-Z0-9]{' . ($length - 1) . '}$/', $randStr);
    }

    public function test_htpasswd(): void
    {
        $username = 'test_user';
        $password = 'password123';
        $salt = '7@F|RkBX1,KYN6Ve2H(F';
        $salted_password = '$apr1$' . $salt . '$' . md5( $salt . $password . $salt );
        $htpassoutput = $username . ':' . $salted_password . ':' . $salt;

        $htpasswd = self::htpasswd($username, $password, $salt );

        $this->assertStringContainsString($username, $htpasswd);
        $this->assertEquals($htpassoutput, $htpasswd);
    }

    public function test_bcr_htpasswd(): void
    {
        $username = 'test_user';
        $password = 'password123';

        $bcrHtpasswd = self::bcr_htpasswd($username, $password);

        $this->assertStringContainsString($username, $bcrHtpasswd);
        $this->assertTrue(password_verify($password, substr($bcrHtpasswd, \strlen($username) + 1)));
    }

    public function test_four_letter_word(): void
    {
        $word = self::four_letter_word();

        $this->assertEquals(4, \strlen($word));
        $this->assertMatchesRegularExpression('/^[a-z]{4}$/', $word);
    }
}
