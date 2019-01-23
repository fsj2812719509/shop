<?php
/**
 * Created by PhpStorm.
 * User: 付仕佳
 * Date: 2019/1/23
 * Time: 8:43
 */
namespace App\Http\Controllers\Login;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\UserModel;
class LoginController extends Controller{
    //登录
    public function login(){
        return view('login.login');
    }
    public function dologin(Request $request){
        $name = $request->input('name');
        $pwd = $request->input('pwd');
        $data = UserModel::where(['name'=>$name])->first();
        if($data){
            if(password_verify($pwd,$data->pwd)){
                $token = substr(md5(time().mt_rand(1,99999)),10,10);
                setcookie('uid',$data->uid,time()+86400,'/','shop.com','false','true');
                setcookie('token',$token,time()+86400,'/','false','true');

                $request->session()->put('token',$token);
                $request->session()->put('uid',$data->uid);

                echo '登录成功';
            }else{
                echo '登录失败,用户名或密码不正确';
                header('refresh:2','/login1');
            }
        }else{
            echo '登录失败，没有此用户';
            header('refresh:2','/login1');
        }

    }
}
