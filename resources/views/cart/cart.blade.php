@extends('layouts.bst')

@section('content')

    <table class="table table-bordered">
        <thead>
        <td>商品名称</td>
        <td>商品价格</td>
        <td>操作</td>
        </thead>
        <tbody>
        @foreach($list as $v)
            <tr>
                <td><a href="/goodsdetail/{{$v['goods_id']}}">{{$v['goods_name']}}</a></td>
                <td>{{$v['price']/100}}</td>
                <td>
                    <a href="/cartdel2/{{$v['goods_id']}}" class="del_goods">删除</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <a href="/order">结算</a>
@endsection


