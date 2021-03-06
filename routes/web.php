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
Route::get('/ce','Pay\IndexController@ce');
//支付
Route::get('/pay/alipay/test','Pay\AlipayController@test');//测试
Route::get('/pay/o/{order_id}','Pay\AlipayController@orderpay')->middleware('check.login.token');         //订单支付
Route::post('/pay/alipay/notify','Pay\AlipayController@aliNotify'); //异步通知
Route::get('/pay/alipay/return','Pay\AlipayController@alireturn'); //支付宝支付 同步回调

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//文件上传
Route::get('/upload','Upload\UploadController@upload');
Route::post('/uploads','Upload\UploadController@uploads');

//考试登录
Route::get('/login1','Login\LoginController@login');
Route::post('/dologin1','Login\loginController@dologin');
Route::get('/pwd','Login\LoginController@update');
Route::post('/update/pwd','Login\LoginController@doupdate');

//微信
Route::get('/Wechat/test','Wechat\WechatController@test');
Route::get('/Wechat/valid','Wechat\WechatController@validToken');
Route::get('/Wechat/valid1','Wechat\WechatController@validToken1');
Route::post('/Wechat/valid1','Wechat\WechatController@wxEvent');        //接收微信服务器事件推送
Route::post('/Wechat/valid','Wechat\WechatController@validToken');


Route::get('/Wechat/createmenu','Wechat\WechatController@createMenu');
Route::post('/Wechat/creatmenu','Wechat\WechatController@createMenu');



//微信支付
Route::get('/weixin/pay/test/{order_id}','Wechat\PayController@test');     //微信支付测试
Route::post('/weixin/pay/notice','Wechat\PayController@notice');     //微信支付通知回调
Route::get('/deciphering/{url}','Wechat\PayController@deciphering');
Route::post('/success','Wechat\PayController@success');
Route::get('/win','Wechat\PayController@win');

//微信登录
//Route::get('/weixinlogin','Wechat\WechatController@weixinlogin');

//jssdk
Route::get('/weixin/jssdk/test','Wechat\WechatController@jssdkTest');       // 测试

//自定义菜单
Route::get('/wechat/custommenu','Wechat\WechatController@customMenu');
Route::post('/wechat/custommenu','Wechat\WechatController@customMenu');

Route::get('/wechat/custommenuview','Wechat\WechatController@customMenuview');









