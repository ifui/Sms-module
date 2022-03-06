<?php

use Modules\Sms\Http\Controllers\V1\SmsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// throttle:oneMinuteSend 限制 1 分钟发送 1 次
// 参考配置：Providers/RouteServiceProvider configureRateLimiting 方法
Route::prefix('v1/sms')->middleware(['throttle:oneMinuteSend'])->group(function () {
    Route::get('aliyun/login', [SmsController::class, 'loginCode']);
    Route::get('aliyun/register', [SmsController::class, 'registerCode']);
});
