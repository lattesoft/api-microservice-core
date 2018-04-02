<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 2/24/2018
 * Time: 7:20 PM
 */

namespace Finiz\Util;

class ICryptor
{
    public static function encrypt($message)
    {
        $secret_key = config('web.icryptor.key');
        $secret_iv = config('web.icryptor.secret.key');
        $encrypt_method = 'AES-256-CBC';
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = base64_encode(openssl_encrypt($message, $encrypt_method, $key, 0, $iv));
        return $output;
    }

    public static function decrypt($message)
    {
        $secret_key = config('web.icryptor.key');
        $secret_iv = config('web.icryptor.secret.key');
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_decrypt(base64_decode($message, $encrypt_method, $key, 0, $iv));
        return $output;
    }
}