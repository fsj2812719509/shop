<?php
/**
 * Created by PhpStorm.
 * User: 付仕佳
 * Date: 2019/1/8
 * Time: 14:01
 */
namespace App\Http\Controllers\Cart;

use App\Model\CartModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\GoodsModel;
class IndexController extends Controller{

    public $uid;                    // 登录UID


    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->uid = session()->get('uid');
            return $next($request);
        });

    }

    public function index(Request $request){

        //echo __METHOD__;
        /*$goods = session()->get('cart_goods');
        if(empty($goods)){
            echo '购物车里还没有商品呦';
        }else{
            foreach($goods as $k=>$v){
                echo 'goods ID: '.$v;echo '</br>';
                $detail = GoodsModel::where(['goods_id'=>$v])->first()->toArray();
                echo '<pre>';print_r($detail);echo '</pre>';
            }
        }*/

        $cart_goods = CartModel::where(['uid'=>$this->uid])->get()->toArray();
        if(empty($cart_goods)){
            echo '购物车是空的';
            exit;
        }

        if($cart_goods){
            foreach ($cart_goods as $k=>$v){
                $goods_info = GoodsModel::where(['goods_id'=>$v['goods_id']])->first()->toArray();
                $goods_info['num'] = $v['num'];
                $list[] = $goods_info;
            }
            $data = [
                'list'  => $list
            ];
            return view('cart.cart',$data);
        }

    }


    /** 添加购物车 */
    public function cartadd(Request $request){

        //echo session()->get('u_token');exit;

        $goods_id=$request->input('goods_id');
        $num=$request->input('goods_num');
        //echo '1';die;
        $cart_goods = session()->get('cart_goods');
        session()->push('cart_goods',$goods_id);

        //减库存
        $goods_where = ["goods_id"=>$goods_id];
        $store = GoodsModel::where($goods_where)->value('store');
        if($store<=0){
            echo '库存不足';
            exit;
        }

        $res = GoodsModel::where(['goods_id'=>$goods_id])->decrement('store');
        //写入购物车表
        $data = [
            'goods_id'  => $goods_id,
            'num'       => $num,
            'add_time'  => time(),
            'uid'       => $this->uid,
            'session_token' => session()->get('u_token')
        ];

        //print_r($data);exit;
        $cid = CartModel::insertGetId($data);
        if(!$cid){
            $response = [
                'error' => 5002,
                'msg'   => '添加购物车失败，请重试'
            ];
            return $response;
        }


        $response = [
            'error' => 0,
            'msg'   => '添加成功'
        ];
        return $response;


    }

    /** 删除 */
    public function cartdel($goods_id)
    {
        //判断此商品在购物车中
        $goods = session()->get('cart_goods');
        echo '<pre>';
        print_r($goods_id);
        echo '</pre>';
        die;
        if (in_array($goods_id, $goods)) {
            //执行删除
            foreach ($goods as $k => $v) {
                if ($goods_id == $v) {
                    $res = session()->pull('cart_goods.' . $k);
                    if ($res) {
                        echo '删除成功';
                    } else {
                        echo '删除失败';
                    }
                }
            }
        } else {
            echo '没有此商品';
        }

    }

    /** 删除 */
    public function cartdel2($abc){
        /*echo $abc;
        echo $this->uid;
        exit;*/
        $res = CartModel::where(['uid'=>$this->uid,'goods_id'=>$abc])->delete();
        if($res){
            echo '商品ID:  '.$abc . ' 删除成功1';
        }

    }

}
