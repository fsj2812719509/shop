<?php

namespace App\Admin\Controllers;

use App\Model\WechatModel;
use App\Model\WeixinChatModel;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp;



class WeixinController extends Controller
{
    use HasResourceActions;
    protected $redis_weixin_access_token = 'str:weixin_access_token';     //微信 access_token

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WechatModel);

        $grid->id('Id');
        $grid->uid('Uid');
        $grid->openid('Openid')->display(function ($openid){
            return '<a href="weixinService?openid='.$openid.'">'.$openid.'</a>';
        });
        $grid->add_time('Add time');
        $grid->nickname('Nickname');
        $grid->sex('Sex');
        $grid->headimgurl('Headimgurl')->display(function ($lmg_url){
            return '<img src="'.$lmg_url.'">';
        });
        $grid->subscribe_time('Subscribe time');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(WechatModel::findOrFail($id));

        $show->id('Id');
        $show->uid('Uid');
        $show->openid('Openid');
        $show->add_time('Add time');
        $show->nickname('Nickname');
        $show->sex('Sex');
        $show->headimgurl('Headimgurl');
        $show->subscribe_time('Subscribe time');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new WechatModel);

        $form->number('uid', 'Uid');
        $form->text('openid', 'Openid');
        $form->number('add_time', 'Add time');
        $form->text('nickname', 'Nickname');
        $form->number('sex', 'Sex');
        $form->text('headimgurl', 'Headimgurl');
        $form->number('subscribe_time', 'Subscribe time');

        return $form;
    }

    /**
     * 消息群发
     */
    public function sendMsgView(Content $content){
        return $content
            ->header('微信')
            ->description('群发消息')
            ->body(view('admin.weixin.send_msg'));
    }
    public function sendMsg(){
        //获取用户openID
        $list = WechatModel::all()->puck('openid')->take(10)->toArray();
        //群发消息

        echo '<pre>';print_r($list);echo '</pre>';
        echo '<pre>';print_r($_POST);echo '</pre>';
    }

    /**
     * 客服聊天
     */
    public function weixinService(Content $content){
        $openid = $_GET['openid'];
        $opid = WechatModel::where(['openid'=>$openid])->first();

        $mbx=WeixinChatModel::where(['openid'=>$openid])->get();

        $data = [
            'headimgurl' => $opid['headimgurl'],
            'openid'=>$opid['openid'],
            'nickname'=>$opid['nickname'],
            'info'=>$mbx,
        ];


        return $content
            ->header($opid['nickname'])
            ->description('description')
            ->body(view('admin.service',$data));
    }
    /**
     * 获取微信AccessTokenk
     */
    public function getWXAccessToken()
    {

        //获取缓存
        $token = Redis::get($this->redis_weixin_access_token);
        if(!$token){        // 无缓存 请求微信接口
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WEIXIN_APPID').'&secret='.env('WEIXIN_APPSECRET');
            $data = json_decode(file_get_contents($url),true);

            //记录缓存
            $token = $data['access_token'];
            Redis::set($this->redis_weixin_access_token,$token);
            Redis::setTimeout($this->redis_weixin_access_token,3600);
        }
        return  $token;

    }


    public function dochat(Request $request){

        $message = $request->input('message');
        $openid = $request->input('openid');


        //1 获取access_token拼接请求接口

        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$this->getWXAccessToken();
        //echo $url;die;
        //2 请求微信接口
        $client = new GuzzleHttp\Client(['base_uri' => $url]);
        $data = [
            "touser"    => $openid,
            "msgtype"   => "text",
            "text"  => [
                'content'   =>$message
            ]
        ];
        $arr = $client->request('POST',$url,[
            'body' => json_encode($data,JSON_UNESCAPED_UNICODE)
        ]);
        //3 解析微信接口返回信息
        $response_arr = json_decode($arr->getBody(),true);
        //print_r($response_arr);
        if($response_arr['errcode'] == 0){
            echo "success";
        }else{
            echo "fail，请重试";echo '</br>';
            echo $response_arr['errmsg'];
        }
    }


}
