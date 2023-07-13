<?php

namespace Urisoft\App\Console;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Exception;
use InvalidArgumentException;
use Symfony\Component\Filesystem\Filesystem;

class EncryptionKey
{
    public static function generate_key(): string
    {
		$key = Key::createNewRandomKey();
		return $key->saveToAsciiSafeString();
    }
}
