@extends('layouts.bst')

@section('content')
    <p>这里是goods Content.
    <table class="table table-bordered">
        <thead>
            <td>UID</td>
            <td>商品名称</td>
            <td>商品价格</td>
        </thead>
        <tbody>
        @foreach($list as $v)
            <tr>
                <td>{{$v['goods_id']}}</td>
                <td><a href="/goodsdetail/{{$v['goods_id']}}">{{$v['goods_name']}}</a></td>
                <td>{{$v['price']/100}}</td>

            </tr>
        @endforeach
        </tbody>
    </table>
@endsection


