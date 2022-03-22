<?php

namespace Modules\Sms\Http\Controllers\V1;

use App\Exceptions\CodeException;
use App\Http\Controllers\Controller;
use Modules\Sms\Driver\AliyunSms;
use Modules\Sms\Http\Requests\V1\SmsRequest;
use Modules\Sms\Redis\LoginCodeRedis;
use Modules\Sms\Redis\RegisterCodeRedis;

class SmsController extends Controller
{
    /**
     * 请求登录验证码
     *
     * @param SmsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function loginCode(SmsRequest $request)
    {
        $code = $this->sendCode();

        // 存入redis，默认验证过期时长 5 分钟
        LoginCodeRedis::setex($request->phone, 5 * 60, $code);

        return success();
    }

    /**
     * 检查登录验证码
     *
     * @param SmsRequest $request
     * @return Boolean
     */
    public static function checkLoginCode($phone, $code)
    {
        if (LoginCodeRedis::get($phone) == $code) {
            LoginCodeRedis::del($phone);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 注册验证码发送
     *
     * @param SmsRequest $request
     * @return void
     */
    public function registerCode(SmsRequest $request)
    {
        $code = $this->sendCode();
        // 存入redis，默认验证过期时长 5 分钟
        RegisterCodeRedis::setex($request->phone, 5 * 60, $code);

        return success();
    }

    /**
     * 检查注册验证码
     *
     * @param SmsRequest $request
     * @return Boolean
     */
    public static function checkRegisterCode($phone, $code)
    {
        if (RegisterCodeRedis::get($phone) == $code) {
            RegisterCodeRedis::del($phone);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 通用方法：发送验证码
     *
     * @return string code
     */
    private function sendCode()
    {
        $phone = request()->input('phone');

        // 生成验证码
        $code = mt_rand(100000, 999999);

        // 判断是否是测试环境
        if (app()->runningUnitTests()) {
            return $code;
        }

        $result = AliyunSms::send($phone, $code);
        $resultMessage = $result->body->message;
        $resultCode = $result->body->code;

        if ($resultMessage !== 'OK') {
            if ($resultCode === 'isv.BUSINESS_LIMIT_CONTROL') {
                throw new CodeException('sms::code.7001');
            }
            if ($resultCode === 'isv.MOBILE_NUMBER_ILLEGAL') {
                throw new CodeException('sms::code.7002');
            }
            throw new CodeException('sms::code.7003');
        }

        return $code;
    }
}
