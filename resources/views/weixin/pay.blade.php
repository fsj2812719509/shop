{{$code_url}}
{{-- 用户登录--}}
<input type="hidden" value="{{$code_url}}" id="code1">
@extends('layouts.bst')
    <div id="code"></div>
<script src="{{URL::asset('/js/jquery-3.2.1.min.js')}}"></script>
<script src="{{URL::asset('/bootstrap/js/jquery.qrcode.min.js')}}"></script>
<script>
    var code1 =$("#code1").val();
    $("#code").qrcode({
        render: "canvas", //table方式
        width: 200, //宽度
        height:200, //高度
        text:code1//任意内容
    });
</script>



