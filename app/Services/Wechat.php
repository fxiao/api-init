<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;

/**
 * 微信相关服务
 * Class Wechat
 * @package App\Service
 */
class Wechat
{


    public function __construct()
    {

    }

    public function getAccessToken()
    {

        if (Cache::has('wx_access_token')) {
            return Cache::get('wx_access_token');
        } else {
            $access_token = $this->getToken();
	    if(!isset($access_token['access_token']) || $access_token['access_token'] == "") {
                abort($access_token['errcode'] ,$access_token['errmsg']);
            }
            Cache::put('wx_access_token', $access_token['access_token'], 110);
            return $access_token['access_token'];
        }
    }

    /**
     * jsapi_ticket是公众号用于调用微信JS接口的临时票据
     * @return bool|string
     */
    public function getTicket()
    {
        if(Cache::has('wx_jsapi_ticket')) {
            return Cache::get('wx_jsapi_ticket');
        }else{

            $result = http_curl('https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$this->getAccessToken().'&type=jsapi');
            $result = json_decode($result, true);
            if(!$result['ticket']) {
                abort($result['errcode'], $result['errmsg']);
            }
            Cache::put('wx_jsapi_ticket', $result['ticket'], 110);
            return $result['ticket'];
        }
    }

    private function getToken()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . env('APPID') . '&secret=' . env('APPSECRET');
        $json_data = http_curl($url,'post');
        return json_decode($json_data, true);
    }
}
