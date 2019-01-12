<?php
/**
 * Created by PhpStorm.
 * User: 付仕佳
 * Date: 2019/1/8
 * Time: 14:01
 */
namespace App\Http\Controllers\Pay;

use App\Model\OrderModel;
use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\GoodsModel;
class IndexController extends Controller{
    public function ce(){
        $url='http://zty.52self.cn/';
        $client=new Client(['base_uri'=>$url,'timeout'=>2.0,]);
        $response=$client->request('GET','/Order.php');
        echo $response->getBody();
    }





    /** 支付 */
    public function pay($order_id){

        $orderInfo = OrderModel::where(['order_id'=>$order_id])->first();

        if(!$orderInfo){
            echo '订单不存在';
            exit;
        }
        //检查订单状态 是否已支付 已过期 已删除
        if($orderInfo->pay_time>0){
            echo '此订单已被支付';exit;
        }

        //支付成功
        $res = OrderModel::Where(['order_id'=>$order_id])->update(['pay_time'=>time(),'pay_amount'=>rand(1111,9999),'is_pay'=>1]);

        //积分
        $score = 0;
        $data = OrderModel::where(['order_id'=>$order_id])->first();
        $res = $data['pay_amount']/100;
        $uid = session()->get('uid');
        $where = ['uid'=>$uid];
        $data1 = UserModel::where($where)->update(['score'=>$score]);


        if($res){
            echo '支付成功';
           // return redirect('/order');
            header('Refresh:2;url=/orderlist');
        }else{
            echo '支付失败';
        }

    }
}