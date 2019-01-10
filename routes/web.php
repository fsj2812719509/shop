<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    //echo date('Y-m-d H:i:s');
    return view('welcome');
});

Route::get('/adduser','User\UserController@add');

//路由跳转
Route::redirect('/hello1','/world1',301);
Route::get('/world1','Test\TestController@world1');

Route::get('hello2','Test\TestController@hello2');
Route::get('world2','Test\TestController@world2');


//路由参数
Route::get('/user/{uid}','User\UserController@user');
Route::get('/month/{m}/date/{d}','Test\TestController@md');
Route::get('/name/{str?}','Test\TestController@showName');

// View视图路由
Route::view('/mvc','mvc');
Route::view('/error','error',['code'=>403]);


// Query Builder
Route::get('/query/get','Test\TestController@query1');
Route::get('/query/where','Test\TestController@query2');


Route::get('/test','Test\TestController@test1');

//用户注册
Route::get('/userreg','User\UserController@reg');
Route::post('/userreg','User\UserController@doReg');

//用户登录
Route::get('/userlogin','User\UserController@login');
Route::post('/userlogin','User\UserController@dologin');


//模板引入静态文件
Route::get('/mvc/test1','Mvc\MvcController@test1');

Route::get('/mvc/bst','Mvc\MvcController@bst');

//checkCookie
Route::get('/checkcookie','Test\TestController@checkCookie')->middleware('check.cookie');
Route::get('/cart','Cart\IndexController@index')->middleware('check.login.token');
Route::post('/cartadd','Cart\IndexController@cartadd')->middleware('check.login.token');
Route::get('/cartdel/{goods_id}','Cart\IndexController@cartdel')->middleware('check.login.token');

//展示商品
Route::get('/goods','Goods\IndexController@goods');
//商品详情
Route::get('/goodsdetail/{goods_id}','Goods\IndexController@goodsDetail');
//商品删除
Route::get('/cartdel2/{goods_id}','Cart\IndexController@cartdel2')->middleware('check.login.token');

//生成订单
Route::get('/order','Order\IndexController@order')->middleware('check.login.token');
//订单展示
Route::get('/orderlist','Order\IndexController@orderlist');

//支付
Route::get('/pay/{order_id}','Pay\IndexController@pay')->middleware('check.login.token');






