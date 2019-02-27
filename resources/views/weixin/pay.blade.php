{{$code_url}}
{{-- 用户登录--}}
<input type="hidden" value="{{$code_url}}" id="code1">
@extends('layouts.bst')

@section('content')
    <div id="code"></div>
@endsection
<script>
    var code1 =$("#code1").val();
    $("#code").qrcode({
        render: "canvas", //table方式
        width: 200, //宽度
        height:200, //高度
        text:code1//任意内容
    });
</script>



