<?php

return [
    'name' => 'Sms',
    /**
     * 阿里云短信配置
     */
    'aliyun' => [
        'accessKeyId' => env('SMS_ALIYUN_ACCESS_KEY_ID', ''),
        'accessSecret' => env('SMS_ALIYUN_ACCESS_SECRET', ''),
        // 地域
        'regionId' => env('SMS_ALIYUN_REGION_ID', ''),
        // 短信签名名称
        'signName' => env('SMS_ALIYUN_SIGN_NAME', ''),
        // 短信模板ID
        'templateCode' => env('SMS_ALIYUN_TEMPLATE_CODE', ''),
    ],
];
