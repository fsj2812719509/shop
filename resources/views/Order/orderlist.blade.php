@extends('layouts.bst')

@section('content')
    <h2>订单</h2>
    <table class="table table-bordered">
        <thead>
        <td>订单id</td>
        <td>订单号</td>
        <td>订单价格</td>
        </thead>
        <tbody>
        @foreach($list as $v)
            <tr>
                <td>{{$v['order_id']}}</td>
                <td>{{$v['order_name']}}</td>
                <td>{{$v['order_price']}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
