<?php

namespace App\Services;

class Encrypt
{
    protected $method = 'AES-128-CBC';
    protected $iv;
    protected $key;

    public function __construct()
    {
        $this->key = env('APP_KEY', '9VJWwBF9Va3SAjaPpuxisIjIabt2C13O');
        $this->iv = substr($this->key, 0, 16);
    }

    public function encode($data)
    {
        return openssl_encrypt($this->safe_encode($data), $this->method, $this->key, 0, $this->iv);
    }

    public function decode($data)
    {
        return $this->safe_decode(openssl_decrypt($data, $this->method, $this->key, 0, $this->iv));
    }

    public function safe_encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function safe_decode($data)
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
