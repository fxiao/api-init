<?php

namespace App\Services;

class Pwt
{
    protected $encrypt;
    protected $payload;
    protected $request;
    protected $msg = '不要点那么快嘛！';

    public function __construct()
    {
        $this->encrypt = new Encrypt;
    }

    public function setMsg(string $msg)
    {
        $this->msg = $msg;
        return $this;
    }

    public function setPhone(string $phone)
    {
        abort_unless(is_phone($phone), 429, $this->msg);

        $this->payload['phone'] = $phone;
        $this->payload['phone_created_at'] = time();
        $this->payload['phone_ttl'] = time() + env('SMS_TTL', 1);
        $this->payload['phone_exp'] = time() + env('SMS_EXP', 300);
        $this->payload['code'] = mt_rand(1000, 9999);

        return $this;
    }

    public function setUri(string $uri = "default")
    {
        abort_if(is_null($uri), 429, $this->msg);
        abort_unless(array_key_exists($uri, app('config')['sms']['tmpl']), 400, '当前页面是不可以发验证码哦！');

        $this->payload['uri'] = $uri;
        $this->payload['uri_created_at'] = time();
        $this->payload['uri_ttl'] = time() + env('SMS_TTL', 3);
        $this->payload['uri_exp'] = time() + env('SMS_EXP', 600);

        return $this;
    }

    public function generate(): string
    {
        return $this->encrypt->encode(json_encode($this->payload, JSON_UNESCAPED_SLASHES));
    }

    public function sms()
    {
        $sms = new Sms;

        return $sms->send($this->payload['phone'], $this->payload['uri'], $this->payload['code']);
    }

    /**
     * @param array $data 只接收 phone, uri, code
     */
    public function verify(string $pwt=null, string $data=null, string $code=null)
    {
        abort_if(is_null($pwt), 429, $this->msg);

        $this->payload = json_decode($this->encrypt->decode($pwt), JSON_UNESCAPED_SLASHES);

        if (!is_null($code)) {
            abort_unless(
                array_key_exists('code', $this->payload) && $this->payload['code'] == $code,
                400,
                '验证码错误！'
            );
        }

        if (is_phone($data)) {
            abort_unless(
                $this->payload['phone'] == $data
                && $this->payload['phone_exp'] >= time()
                && $this->payload['phone_ttl'] <= time(),
                429,
                $this->msg);
        } else {
            abort_unless(
                $this->payload['uri'] == $data
                && $this->payload['uri_exp'] >= time()
                && $this->payload['uri_ttl'] <= time(),
                429,
                $this->msg);
        }

        return $this;
    }

}

