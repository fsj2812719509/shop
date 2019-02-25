<h2><b style="color: red">客服聊天</b>:{{$nickname}}</h2>

<div class="chat" id="chat_div">
    @foreach($info as $v)
        <table>
            <tr>
                <td><h4>{{$nickname}}:</h4></td>
                <td>{{$v['message']}}</td>
            </tr>
        </table>
    @endforeach
</div>

<form action="" class="form-inline">
    {{csrf_field()}}
    <input type="hidden" value="{{$openid}}" id="openid">
    <input type="hidden" value="1" id="msg_pos">
    <textarea name="" id="send_msg" cols="100" rows="5"></textarea>
    <input type="button" id="btn" value="发送">
</form>
<script src="{{URL::asset('/js/jquery-3.2.1.min.js')}}"></script>

<script>
    $(function(){
        $('#btn').click(function(){
            //获取ID和内容
            var send_msg = $("#send_msg").val();
            var openid = $("#openid").val();

            $.post(
                'weixinChat',
                {message:send_msg,openid:openid},
                function(msg){
                    console.log(msg);
                }
            )
        })
    })
</script>
