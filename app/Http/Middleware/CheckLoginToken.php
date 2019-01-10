<?php
/**
 * Created by PhpStorm.
 * User: 付仕佳
 * Date: 2019/1/8
 * Time: 14:14
 */
namespace App\Http\Middleware;

use Closure;
class CheckLoginToken{

    public function handle($request , closure $next){
        if(!$request->session()->get('u_token')){
            return redirect('/userlogin');
            echo "请先登录";
            exit;
        }
        return $next($request);
    }
}