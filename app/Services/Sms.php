<?php

namespace App\Services;

use Flc\Dysms\Client;
use Flc\Dysms\Request\SendSms;

class Sms
{
    private $client;
    private $sendSms;
    private $phone;
    private $tmpl;
    private $code;
    private $sign;
    private $outid;

    public function __construct()
    {
        $config = [
            'accessKeyId' => app('config')['sms']['accessKeyId'],
            'accessKeySecret' => app('config')['sms']['accessKeySecret'],
        ];

        $this->client = new Client($config);
        $this->sendSms = new SendSms;
    }

    public function send(string $to, string $tmpl = 'default', string $code = '1234') : array
    {
        $this->phone = $to;
        $this->tmpl = $tmpl;
        $this->code = $code;

        return $this->execute();
    }

    public function execute() : array
    {
        $ret  = $this->client->execute($this->getSendSms());
        $code = $ret->Code;

        $msg = $this->getErrMsgMap()[$code] ?? '服务器错误，短信发送失败';

        abort_unless(str_equal('ok', $code), 400, $msg);

        return ['message' => $msg];
    }

    public function setPhone(string $phone)
    {
        $this->phone = $phone;
        return $this;
    }

    public function setSign(string $sign = null)
    {
        $this->sign = $sign;
        return $this;
    }

    public function setCode(string $code = '1234')
    {
        $this->code = $code;
        return $this;
    }

    public function setTmpl(string $tmpl = null )
    {
        $this->tmpl = $tmpl;
        return $this;
    }

    public function setOutId(string $outid)
    {
        $this->outid = $outid;
        return $this;
    }

    protected function getSendSms()
    {
        abort_unless(is_phone($this->phone), 400, '不是中国大陆手机号！');

        if (!($tmpl_code = app('config')['sms']['tmpl'][$this->tmpl])) {
            abort(400, $this->tmpl . ' SMS 模块不存在！');
        }

        $this->sendSms
        ->setPhoneNumbers($this->phone)
        ->setSignName($this->sign ?? app('config')['sms']['sign'])
        ->setTemplateCode($tmpl_code);

        if ($outid = app('config')['outid']) {
            $this->sendSms->setOutId($outid);
        }

        if ($this->code) {
            $this->sendSms->setTemplateParam([
                'code' => $this->code,
            ]);
        }

        return $this->sendSms;
    }

    public function getErrMsgMap()
    {
        return [
            'OK'                              => '发送成功',
            'isp.RAM_PERMISSION_DENY'         => 'RAM权限DENY',
            'isv.OUT_OF_SERVICE'              => '业务停机',
            'isv.PRODUCT_UN_SUBSCRIPT'        => '未开通云通信产品的阿里云客户',
            'isv.PRODUCT_UNSUBSCRIBE'         => '产品未开通',
            'isv.ACCOUNT_NOT_EXISTS'          => '账户不存在',
            'isv.ACCOUNT_ABNORMAL'            => '账户异常',
            'isv.SMS_TEMPLATE_ILLEGAL'        => '短信模板不合法',
            'isv.SMS_SIGNATURE_ILLEGAL'       => '短信签名不合法',
            'isv.INVALID_PARAMETERS'          => '参数异常',
            'isp.SYSTEM_ERROR'                => '系统错误',
            'isv.MOBILE_NUMBER_ILLEGAL'       => '非法手机号',
            'isv.MOBILE_COUNT_OVER_LIMIT'     => '手机号码数量超过限制',
            'isv.TEMPLATE_MISSING_PARAMETERS' => '模板缺少变量',
            'isv.BUSINESS_LIMIT_CONTROL'      => '业务限流（达到发送次数限制）,请稍后操作',
            'isv.INVALID_JSON_PARAM'          => 'JSON参数不合法，只接受字符串值',
            'isv.BLACK_KEY_CONTROL_LIMIT'     => '黑名单管控',
            'isv.PARAM_LENGTH_LIMIT'          => '参数超出长度限制',
            'isv.PARAM_NOT_SUPPORT_URL'       => '不支持URL',
            'isv.AMOUNT_NOT_ENOUGH'           => '账户余额不足',
        ];
    }
}
