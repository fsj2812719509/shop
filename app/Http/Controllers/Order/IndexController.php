<?php
/**
 * Created by PhpStorm.
 * User: 付仕佳
 * Date: 2019/1/8
 * Time: 14:01
 */
namespace App\Http\Controllers\Order;

use App\Model\CartModel;
use App\Model\OrderModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\GoodsModel;
class IndexController extends Controller{
    /** 生成订单 */
    public function order(){

        //获取uid
        $uid = session()->get('uid');

        //查询购物车列表
        $res = CartModel::where(['uid'=>$uid])->get()->toArray();
        if(empty($res)){
            echo '购物车里还没有商品哦，不能结算';
        }
        $order_price = 0;
        if($res){
            foreach($res as $k=>$v){
                //查询商品表
                $goods_info = GoodsModel::where(['goods_id'=>$v['goods_id']])->first()->toArray();
                $goods_info['num'] = $v['num'];
                $list[] = $goods_info;

                //计算订单价格 = 商品数量 * 单价
                $order_price += $goods_info['price']/100 * $v['num'];
            }
            //生成订单
            $order_sn = OrderModel::generateOrderSN();
            echo $order_sn;
            $data = [
                'order_name'=>$order_sn,
                'uid'=>session()->get('uid'),
                'add_time'=>time(),
                'order_price'=>$order_price
            ];
            $order_id = OrderModel::insertGetId($data);
            if(!$order_id){
                echo'生成订单失败';
            }
            echo '下单成功,订单号：'. $order_sn.' 跳转支付';

            //清空购物车
            CartModel::where(['uid'=>session()->get('uid')])->delete();
        }

    }

    /** 订单列表展示 */
    public function orderlist(){
        //查询列表
        $list = OrderModel::get()->toArray();
        $data = [
            'title'     => 'XXXX',
            'list'      => $list
        ];

        return view('order.orderlist',$data);
    }
}