<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\UserModel;

class UserController extends Controller
{
    //

    public function user($uid)
    {
        echo $uid;
    }

    public function test()
    {
        echo '<pre>';print_r($_GET);echo '</pre>';
    }

    public function add()
    {
        $data = [
            'name'      => str_random(5),
            'age'       => mt_rand(20,99),
            'email'     => str_random(6) . '@gmail.com',
            'reg_time'  => time()
        ];

        $id = UserModel::insertGetId($data);
        var_dump($id);
    }


    /**
     * 用户注册
     * 2019年1月3日14:26:56
     * liwei
     */
    public function reg()
    {
        return view('users.reg');
    }

    public function doReg(Request $request)
    {
        echo '<pre>';print_r($_POST);echo '</pre>';
        $u_name = $request->input('u_name');
        $u =UserModel::where(['name'=>$u_name])->first();
        if($u){
           die('用户已存在');
        }
        $u_pwd = $request->input('u_pwd');
        $pwd = password_hash($u_pwd,PASSWORD_BCRYPT);

        $data = [
            'name'  => $request->input('u_name'),
            'age'  => $request->input('u_age'),
            'email'  => $request->input('u_email'),
            'pwd'=>$pwd,
            'reg_time'  => time(),
        ];

        $uid = UserModel::insertGetId($data);
        var_dump($uid);

        if($uid){
            echo '注册成功';
            header('refresh:2;/userlogin');
        }else{
            echo '注册失败';
        }
    }

    /** 用户登录 */
    public function login(){
        return view('users.login');
    }

    public function dologin(Request $request){
        $name = $request->input('name');
        $pwd = $request->input('pwd');
        $data = UserModel::where(['name'=>$name])->first();
        if($data){
            if(password_verify($pwd,$data->pwd)){
                $token = substr(md5(time().mt_rand(1,99999)),10,10);
                setcookie('uid',$data->uid,time()+86400,'/','shop.com','false','true');
                setcookie('token',$token,time(),time()+8460,'/','false','true');

                $request->session()->put('u_token',$token);
                $request->session()->put('uid',$data->uid);

                echo '登录成功';
            }else{
                echo '登录失败';
            }
        }else{
            echo "登录失败，没有此用户";
            header("refresh:2;/userlogin");
        }
    }

    public function center(Request $request)
    {

        if($_COOKIE['token'] != $request->session()->get('u_token')){
            die("非法请求");
        }else{
            echo '正常请求';
        }
        echo 'u_token: '.$request->session()->get('u_token');echo '</br>';
        //echo '<pre>';print_r($request->session()->get('u_token'));echo '</pre>';

        echo '<pre>';print_r($_COOKIE);echo '</pre>';
        die;
        if(empty($_COOKIE['uid'])){
            header('Refresh:2;url=/user/login');
            echo '请先登录';
            exit;
        }else{
            echo 'UID: '.$_COOKIE['uid'] . ' 欢迎回来';
        }
    }



}