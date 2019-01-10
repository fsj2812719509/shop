<?php
/**
 * Created by PhpStorm.
 * User: 付仕佳
 * Date: 2019/1/8
 * Time: 14:01
 */
namespace App\Http\Controllers\Goods;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\GoodsModel;
class IndexController extends Controller{
    /** 展示 */
    public function goods(){
        /** 查询 */
        $list = GoodsModel::get()->toArray();
        $data = [
            'title'     => 'XXXX',
            'list'      => $list
        ];

        return view('goods.goods',$data);
    }

    /** 商品详情 */
    public function goodsDetail($goods_id){
        $goods = GoodsModel::where(['Goods_id'=>$goods_id])->first();

        //判断商品是否存在
        if(!$goods){
            header('Refresh:2;url=/');
            echo "商品不存在";
            exit;
        }

        $data =[
            'goods'=>$goods
        ];
        return view('goods.index',$data);
    }
}