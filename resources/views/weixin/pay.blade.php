{{$code_url}}
{{-- 用户登录--}}
<input type="hidden" value="{{$code_url}}" id="code1">
<input type="hidden" value="{{$order_name}}" id="order_name">
@extends('layouts.bst')
    <div id="code"></div>
<script src="{{URL::asset('/js/jquery-3.2.1.min.js')}}"></script>
<script src="{{URL::asset('/bootstrap/js/jquery.qrcode.min.js')}}"></script>

<script>
    $(function(){
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        var code1 =$("#code1").val();
        var order_name = $("#order_name").val();
        //console.log(order_name);
        $("#code").qrcode({
            render: "canvas", //table方式
            width: 200, //宽度
            height:200, //高度
            text:code1//任意内容
        });
        var success = function(){
            var order_name = $("#order_name").val();
            $.post(
                "/success",
                {order_name:order_name},
                function(msg){
                    if(msg==1){
                        location.href='/win';
                    }
                }
            );
        }
        //计时器
        var s = setInterval(function(){
            success()
        },1000*3)

    })

</script>



