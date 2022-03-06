<?php

namespace Modules\Sms\Driver;

use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;
use Darabonba\OpenApi\Models\Config;

class AliyunSms
{
    /**
     * 使用AK&SK初始化账号Client
     * 参考阿里模版 see: https://next.api.aliyun.com/api/Dysmsapi/2017-05-25/SendSms?params={}&sdkStyle=dara&lang=PHP&tab=DEMO
     *
     * @return Dysmsapi Client
     */
    public static function createClient()
    {
        $config = new Config([
            "accessKeyId" => config('sms.aliyun.accessKeyId'),
            "accessKeySecret" => config('sms.aliyun.accessSecret'),
        ]);
        // 访问的域名
        $config->endpoint = "dysmsapi.aliyuncs.com";
        return new Dysmsapi($config);
    }

    /**
     * 发送短信
     *
     * @param int $phone
     * @param int $code
     * @return AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsResponse
     */
    public static function send($phone, $code)
    {
        $client = self::createClient();
        $sendSmsRequest = new SendSmsRequest([
            "phoneNumbers" => $phone,
            "templateParam" => json_encode([
                'code' => $code,
            ]),
            "signName" => config('sms.aliyun.signName'),
            "templateCode" => config('sms.aliyun.templateCode'),
        ]);
        return $client->sendSms($sendSmsRequest);
    }
}
