<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Model\GoodsModel;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class GoodsController extends Controller
{
    use HasResourceActions;

    public function index(Content $content){
        return $content
            ->header('商品管理')
            ->description('商品列表')
            ->body($this->grid());
    }

    protected function grid()
    {
        $grid = new Grid(new GoodsModel());

        $grid->model()->orderBy('goods_id','desc');     //倒序排序

        $grid->goods_id('商品ID');
        $grid->goods_name('商品名称');
        $grid->store('库存');
        $grid->price('价格');
        $grid->add_time('添加时间')->display(function($time){
            return date('Y-m-d H:i:s',$time);
        });

        return $grid;
    }

    public function edit($id, Content $content)
    {

        return $content
            ->header('商品管理')
            ->description('编辑')
            ->body($this->form()->edit($id));
    }
    //新增
    public function create(Content $content)
    {

        return $content
            ->header('商品管理')
            ->description('添加')
            ->body($this->form());
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

    protected function form()
    {
        $form = new Form(new GoodsModel());
        $form->display('goods_id', '商品ID');
        $form->text('goods_name', '商品名称');
        $form->number('store', '库存');
        $form->currency('price', '价格')->symbol('¥');

        return $form;
    }
}
