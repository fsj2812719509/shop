<?php

namespace App\Http\Controllers\Wechat;

use App\Model\WechatModel;

use App\Model\WeixinChatModel;
use App\Model\WeixinMedia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class WechatController extends Controller
{
    //
    protected $redis_weixin_access_token = 'str:weixin_access_token';     //微信 access_token

    public function test()
    {
        //echo __METHOD__;
        //$this->getWXAccessToken();
        echo 'Token: ' . $this->getWXAccessToken();;
    }

    /**
     * 首次接入
     */
    public function validToken1()
    {
        //$get = json_encode($_GET);
        //$str = '>>>>>' . date('Y-m-d H:i:s') .' '. $get . "<<<<<\n";
        //file_put_contents('logs/weixin.log',$str,FILE_APPEND);
        echo $_GET['echostr'];
    }

    /**
     * 接收微信服务器事件推送
     */
    public function wxEvent()
    {
        $data = file_get_contents("php://input");

        //解析xml
        $xml = simplexml_load_string($data);

        //记录日志
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents('logs/wx_event.log', $log_str, FILE_APPEND);

        $event = $xml->Event;
        $openid = $xml->FromUserName;

        //处理用户发送信息
        if (isset($xml->MsgType)) {
            if ($xml->MsgType == 'text') {
                $msg = $xml->Content;
                $xml_response = $xml_response = '<xml><ToUserName><![CDATA[' . $openid . ']]></ToUserName><FromUserName><![CDATA[' . $xml->ToUserName . ']]></FromUserName><CreateTime>' . time() . '</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[你眨一眨眼睛就变成小星星落入我的心🖤]]></Content></xml>';
                echo $xml_response;

                $data = [
                    'openid' => $openid,
                    'add_time'=>time(),
                    'message'=>$msg
                ];

                $mid = WeixinChatModel::insertGetId($data);

            } elseif ($xml->MsgType == 'image') {
                //视业务需求是否下载保存图片
                if (1) {
                    $file_name = $this->dlWxImg($xml->MediaId);
                    $xml_response = '<xml><ToUserName><![CDATA[' . $openid . ']]></ToUserName><FromUserName><![CDATA[' . $xml->ToUserName . ']]></FromUserName><CreateTime>' . time() . '</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[不要让我看见你，不然见你一次喜欢你一次👧]]></Content></xml>';
                    echo $xml_response;
                    //写入数据库
                    $data = [
                        'openid' => $openid,
                        'add_time' => time(),
                        'msg_type' => 'image',
                        'media_id' => $xml->MediaId,
                        'format' => $xml->Format,
                        'msg_id' => $xml->MsgId,
                        'local_file_name' => $file_name
                    ];

                    $m_id = WeixinMedia::insertGetId($data);
                    var_dump($m_id);
                }
            } elseif ($xml->MsgType == 'voice') {
                $this->dlVoice($xml->MediaId);
                $xml_response = '<xml><ToUserName><![CDATA[' . $openid . ']]></ToUserName><FromUserName><![CDATA[' . $xml->ToUserName . ']]></FromUserName><CreateTime>' . time() . '</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[我那么喜欢你，你喜欢我一下能死吗🤡]]></Content></xml>';
                echo $xml_response;
            } elseif ($xml->MsgType == 'video') {
                $this->dlVideo($xml->MediaId);
                $xml_response = '<xml><ToUserName><![CDATA[' . $openid . ']]></ToUserName><FromUserName><![CDATA[' . $xml->ToUserName . ']]></FromUserName><CreateTime>' . time() . '</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[我的一生，只借一程，从此人山人海，不问归期猪鼻子]]></Content></xml>';
                echo $xml_response;
            }
            exit();
        }


        //判断事件类型
        if ($event == 'subscribe') {
            $sub_time = $xml->CreateTime;

            echo 'openid: ' . $openid;
            echo '<br>';
            echo '$sub_time: ' . $sub_time;

            //获取用户信息
            $user_info = $this->getUserInfo($openid);
            echo '<pre>';
            print_r($user_info);
            echo '</pre>';

            //保存用户信息
            $u = WechatModel::where(['openid' => $openid])->first();
            if ($u) {
                echo '用户已存在';
            } else {
                $user_data = [
                    'openid' => $openid,
                    'add_time' => time(),
                    'nickname' => $user_info['nickname'],
                    'sex' => $user_info['sex'],
                    'headimgurl' => $user_info['headimgurl'],
                    'subscribe_time' => $sub_time,
                ];

                $id = WechatModel::insertGetId($user_data);
                var_dump($id);
            }

        } elseif ($event == 'CLICK') {               //click 菜单
            if ($xml->EventKey == 'kefu01') {
                $this->kefu01($openid, $xml->ToUserName);
            } elseif ($xml->EventKey == 'kefu02') {
                $this->kefu02($openid, $xml->ToUserName);
            }

        }

        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents('logs/wx_event.log', $log_str, FILE_APPEND);
    }


    /**
     * 客服处理
     */
    public function kefu01($openid, $from)
    {
        // 文本消息
        $xml_response = '<xml><ToUserName><![CDATA[' . $openid . ']]></ToUserName><FromUserName><![CDATA[' . $from . ']]></FromUserName><CreateTime>' . time() . '</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[近朱者赤,近你者甜❤]]></Content></xml>';
        echo $xml_response;
    }

    public function kefu02($openid, $from)
    {
        // 文本消息
        $xml_response = '<xml><ToUserName><![CDATA[' . $openid . ']]></ToUserName><FromUserName><![CDATA[' . $from . ']]></FromUserName><CreateTime>' . time() . '</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[你如星辰似海 似万鲸宇宙❤]]></Content></xml>';
        echo $xml_response;
    }

    /**
     * 接收事件推送
     */
    public function validToken()
    {
        //$get = json_encode($_GET);
        //$str = '>>>>>' . date('Y-m-d H:i:s') .' '. $get . "<<<<<\n";
        //file_put_contents('logs/weixin.log',$str,FILE_APPEND);
        //echo $_GET['echostr'];
        $data = file_get_contents("php://input");
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents('logs/wx_event.log', $log_str, FILE_APPEND);
    }

    /**`
     * 获取微信AccessToken
     */
    public function getWXAccessToken()
    {

        //获取缓存
        $token = Redis::get($this->redis_weixin_access_token);
        if (!$token) {        // 无缓存 请求微信接口
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . env('WEIXIN_APPID') . '&secret=' . env('WEIXIN_APPSECRET');
            $data = json_decode(file_get_contents($url), true);

            //记录缓存
            $token = $data['access_token'];
            Redis::set($this->redis_weixin_access_token, $token);
            Redis::setTimeout($this->redis_weixin_access_token, 3600);
        }
        return $token;

    }

    /**
     * 获取用户信息
     * @param $openid
     */
    public function getUserInfo($openid)
    {
        //$openid = 'oLreB1jAnJFzV_8AGWUZlfuaoQto';
        $access_token = $this->getWXAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $access_token . '&openid=' . $openid . '&lang=zh_CN';

        $data = json_decode(file_get_contents($url), true);
        //echo '<pre>';print_r($data);echo '</pre>';
        return $data;
    }

    /**
     * 创建服务号菜单
     */
    public function createMenu()
    {
        //echo __METHOD__;exit;
        // 1 获取access_token 拼接请求接口
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $this->getWXAccessToken();
        //echo $url;echo '</br>';exit;

        //2 请求微信接口
        $client = new GuzzleHttp\Client(['base_uri' => $url]);

        $data = [
            "button" => [
                [
                    "type" => "click",
                    "name" => "老仙婆婆",
                    "key" => "kefu01"
                ],
                [
                    "type" => "click",
                    "name" => "佳佳",
                    "key" => "kefu02"
                ],
                [
                    "name" => "骑猪看夕阳",
                    "sub_button" => [
                        [
                            "type" => "view",
                            "name" => "🐷",
                            "url" => "https://www.baidu.com",
                        ],
                        [
                            "type" => "view",
                            "name" => "🍬",
                            "url" => "https://www.baidu.com",
                        ]
                    ]
                ]

            ]
        ];

        $body = json_encode($data, JSON_UNESCAPED_UNICODE);
        $r = $client->request('POST', $url, [
            'body' => $body
        ]);

        // 3 解析微信接口返回信息

        $response_arr = json_decode($r->getBody(), true);
        //echo '<pre>';print_r($response_arr);echo '</pre>';

        if ($response_arr['errcode'] == 0) {
            echo "菜单创建成功";
        } else {
            echo "菜单创建失败，请重试";
            echo '</br>';
            echo $response_arr['errmsg'];

        }

    }


    /**
     * 保存用户上传的照片
     */

    public function dlWxImg($media_id)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token=' . $this->getWXAccessToken() . '&media_id=' . $media_id;
        //保存图片
        $client = new GuzzleHttp\Client();
        $response = $client->get($url);

        //获取文件名
        $file_info = $response->getHeader('Content-disposition');
        $file_name = substr(rtrim($file_info[0], '"'), -20);

        $wx_image_path = 'wx/images/' . $file_name;
        //保存图片
        $img = Storage::disk('local')->put($wx_image_path, $response->getBody());
        if ($img) {//保存成功

        } else {//保存失败

        }
        return $file_name;
    }

    /**
     * 下载语音文件
     */
    public function dlVoice($media_id)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token=' . $this->getWXAccessToken() . '&media_id=' . $media_id;

        //下载语音文件
        $client = new GuzzleHttp\Client();
        $response = $client->get($url);

        //获取文件名
        $file_info = $response->getHeader('Content-disposition');
        $file_name = substr(rtrim($file_info[0], '"'), -20);

        $wx_image_path = 'wx/vioce/' . $file_name;
        //保存图片
        $voice = $r = Storage::disk('local')->put($wx_image_path, $response->getBody());
        if ($voice) {//保存成功

        } else {//保存失败

        }

    }

    /**
     * 视频
     */
    public function dlVideo($media_id)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token=' . $this->getWXAccessToken() . '&media_id=' . $media_id;
        //视频文件
        $client = new GuzzleHttp\Client();
        $response = $client->get($url);

        //获取文件名
        $file_info = $response->getHeader('Content-disposition');
        $file_name = substr(rtrim($file_info[0], '"'), -20);

        $wx_image_path = 'wx/video/' . $file_name;
        //保存图片
        $video = $r = Storage::disk('local')->put($wx_image_path, $response->getBody());
        if ($video) {//保存成功

        } else {//保存失败

        }

    }

    /**
     * 微信登录
     */
    public function weixinlogin(){
        return view('weixin.login');
    }

    /**
     * 接受code
     */
    /*public function getCode()
    {
//        // 1 获取code
//        $code = $_GET['code'];
//
//        $token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxe24f70961302b5a5&secret=0f121743ff20a3a454e4a12aeecef4be&code='.$code.'&grant_type=authorization_code';
//        $token_json = file_get_contents($token_url);
//        $token_arr = json_decode($token_json,true);
//
//        $access_token = $token_arr['access_token'];
//        $openid = $token_arr['openid'];
//
//        // 3 携带token  获取用户信息
//        $user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
//        $user_json = file_get_contents($user_info_url);
//
//        $user_arr = json_decode($user_json,true);
//
//        $unionid = $user_arr['unionid'];        // 微信用户 unionid
//        echo '<pre>';print_r($user_arr);echo '</pre>';
        $user_arr = [];
        $unionid = 'oTm241U1rFq9ZgnmPx3hXhXd-wssa';
        //4 获取unionid 查询用户信息
        $u = WeixinUser::where(['unionid'=>$unionid])->first();
        var_dump($u);
        if($u){
            //TODO 登录逻辑

        }else{
            // 添加用户表
            $u_data = [
                'name'  => 'wx_'.str_random(8),
            ];

            $uid = UserModel::insertGetId($u_data);

            //添加微信用户表
            $wx_u_data = [
                'uid'       => $uid,
                'openid'    => str_random(16),
                'add_time'  => time(),
                //'sex'       => $user_arr['sex'],
                //'headimgurl'    => $user_arr['headimgurl'],
                'unionid'   => $unionid
            ];

            $wx_id = WechatModel::insertGetId($wx_u_data);

            // 登录
        }

    }*/

    /**
     * 微信jssdk 调试
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function jssdkTest()
    {

        //计算签名

        $jsconfig = [
            'appid' => env('WEIXIN_APPID'),        //APPID
            'timestamp' => time(),
            'noncestr'    => str_random(10),
            //'sign'      => $this->wxJsConfigSign()
        ];
        $sign = $this->wxJsConfigSign($jsconfig);
        $jsconfig['sign'] = $sign;
        //print_r($jsconfig['appid']);
        $data = [
            'jsconfig'  => $jsconfig
        ];
        return view('weixin.jssdk',$data);
    }


    /**
     * 计算JSSDK sign
     */
    public function wxJsConfigSign($param)
    {
        $current_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];     //当前调用 jsapi的 url
        $ticket = $this->getJsapiTicket();
        $str =  'jsapi_ticket='.$ticket.'&noncestr='.$param['noncestr']. '&timestamp='. $param['timestamp']. '&url='.$current_url;
        $signature=sha1($str);
        return $signature;
    }


    /**
     * 获取jsapi_ticket
     * @return mixed
     */
    public function getJsapiTicket()
    {

        //是否有缓存
        $ticket = Redis::get($this->redis_weixin_jsapi_ticket);
        if(!$ticket){           // 无缓存 请求接口
            $access_token = $this->getWXAccessToken();
            $ticket_url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi';
            $ticket_info = file_get_contents($ticket_url);
            $ticket_arr = json_decode($ticket_info,true);

            if(isset($ticket_arr['ticket'])){
                $ticket = $ticket_arr['ticket'];
                Redis::set($this->redis_weixin_jsapi_ticket,$ticket);
                Redis::setTimeout($this->redis_weixin_jsapi_ticket,3600);       //设置过期时间 3600s
            }
        }
        return $ticket;

    }



}

