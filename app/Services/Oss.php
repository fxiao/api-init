<?php

namespace App\Services;

class Oss
{
    private $accessKeyId;
    private $accessKeySecret;
    private $bucketName;
    private $ossServer;
    private $ossHost;
    protected $maxSize;
    protected $expire;

    public function __construct()
    {
        $this->accessKeyId = app('config')['alioss']['accessKeyId'];
        $this->accessKeySecret = app('config')['alioss']['accessKeySecret'];
        $this->bucketName = app('config')['alioss']['bucketName'];
        $this->ossHost = app('config')['alioss']['ossHost'];
        $this->maxSize = 50 * 1024 * 1024;
        $this->expire = 600;
        $this->now = time();
    }

    //设置最大上传文件
    public function setMaxSize($num) {
        $this->maxSize = $num * 1024 * 1024;
    }

    //设置该policy超时时间. 即这个policy过了这个有效时间，将不能访问。
    public function setExpire($seconds) {
        $this->expire = $seconds;
    }

    //用户上传文件时指定的前缀。
    public function setPrefix($prefix) {
        $this->prefix = $prefix;
    }

    //签名
    private function signature($policy, $key)
    {
        $signature = base64_encode(hash_hmac('sha1', $policy, $key, true));
        return $signature;
    }

    //
    private function policy()
    {
        $expire_time = $this->expire + $this->now;
        $expiration = gmt_iso8601($expire_time);

        //最大文件大小.用户可以自己设置
        $condition = array(0=>'content-length-range', 1=>0, 2=>$this->maxSize);
        $conditions[] = $condition;

        // 表示用户上传的数据，必须是以$dir开始，不然上传会失败，这一步不是必须项，只是为了安全起见，防止用户通过policy上传到别人的目录。
//        $start = array(0=>'starts-with', 1=>'$key', 2=>'');
//        $conditions[] = $start;

        $arr = array('expiration'=>$expiration,'conditions'=>$conditions);
        $policy = base64_encode(json_encode($arr));

        return $policy;
    }

    public function response()
    {
        $response = array();
        $response['accessid'] = $this->accessKeyId;
        $response['host'] = $this->ossHost;
        $response['policy'] = $this->policy();
        $response['signature'] = $this->signature($this->policy(), $this->accessKeySecret);
        $response['expire'] = $this->now + $this->expire;
        $response['dir'] = '';
        return json_encode($response);
    }

}
