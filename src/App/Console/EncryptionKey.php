<?php

namespace Urisoft\App\Console;

use Defuse\Crypto\Key;

class EncryptionKey
{
    public static function generate_key(): string
    {
        $key = Key::createNewRandomKey();

        return $key->saveToAsciiSafeString();
    }
}
