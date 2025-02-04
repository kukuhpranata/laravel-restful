<?php

namespace App\Helpers;

class CryptHelper
{
    public static function encrypt($string)
    {
        $cipher = 'aes-128-ecb';
        $secret = base64_decode(config('app.encryption_secret'));
        $encryptedString = openssl_encrypt($string, $cipher, $secret, 0);
        $finalEncrypt = str_replace(['/', '+'], ['-', '_'], $encryptedString);
        return $finalEncrypt;
    }

    public static function decrypt($encryptedString)
    {
        $cipher = 'aes-128-ecb';
        $secret = base64_decode(config('app.encryption_secret'));
        $finalEncrypted = str_replace(['-', '_'], ['/', '+'], $encryptedString);
        $decryptedString = openssl_decrypt($finalEncrypted, $cipher, $secret, 0);
        return $decryptedString;
    }
}
