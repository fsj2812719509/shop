<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    $router->resource('/goods',GoodsController::class);
    $router->resource('/user',UserController::class);
    $router->resource('/weixin',WeixinController::class);
    $router->resource('/weixinMedia',WeixinMediaController::class);
    $router->resource('/send',WeixinSendController::class);
    //群发
    $router->post('/','WeixinSendController@sendAll');

    //永久素材
    $router->get('/formshow','WeixinMediaController@formShow');
    $router->post('/formshow','WeixinMediaController@formTest');

    //客服聊天
    $router->get('/weixinService','WeixinController@weixinService');
    $router->post('/weixinChat','WeixinController@dochat');
    $router->get('/token','WeixinController@getWXAccessToken');








});
