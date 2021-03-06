<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [

        '/cartadd',
        '/pay/alipay/notify',
        '/Wechat/valid1',
        '/admin/weixinChat',
        '/admin/massage',
        '/weixin/pay/notice'

    ];
}
