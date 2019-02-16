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
use Cache;
use PhpParser\Node\Stmt\Catch_;

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
            if($pwd==$data->pwd){
                $token = substr(md5(time().mt_rand(1,99999)),10,10);
                setcookie('uid',$data->uid,time()+36000,'/','shop.com','false','true');
                setcookie('token',$token,time()+36000,'/','false','true');

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
    /** 修改密码 */
    public function update(){
        return view('users.update');
    }
    /** 修改密码 */
    public function doupdate(Request $request){
        $name = $request->input('name');
        $where = ['name' => $name];
        $res = UserModel::where($where)->first();
        if($res){
            if($request->input('pwd')!=$res['pwd']){
                $pwd1 = $request->input('pwd1');
                $pwd2 = $request->input('pwd2');
                if($pwd1!=$pwd2){
                    exit('新密码与确认密码保持一致');
                }
                $dataInfo = ['pwd' => $pwd1];
                $res = UserModel::where($where)->update($dataInfo);
                if($res===false){
                    echo '修改失败';die;
                }else{
                    echo '修改成功,请重新登录';
                    header("refresh:2;url='/login1'");
                }
            }else{
                echo '此密码与原密码一致,不可修改';die;
            }
        }else{
            echo '用户名不存在';die;
        }
    }

}
