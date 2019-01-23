<?php
/**
 * Created by PhpStorm.
 * User: 付仕佳
 * Date: 2019/1/22
 * Time: 9:07
 */

namespace App\Http\Controllers\Upload;

use App\Model\OrderModel;
use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use GuzzleHttp\Client;
class uploadController extends Controller{
    //上传
    public function upload(){
        return view('upload.upload');
    }

    public function uploads(Request $request){
        //接受文件
        $file = $request->file('upload');
        $text = $file->extension();
        if($text!='pdf'){
            echo '请上传pdf形式的文件';exit;
        }
        $res = $file->storeAs(date('Ymd'),str_random(5).'.pdf');
        if($res){
            echo '上传成功';
        }

    }

}