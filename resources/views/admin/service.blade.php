<h2><b style="color: red">客服聊天</b>:{{$nickname}}</h2>

<div class="chat" id="chat_div">
    @foreach($info as $v)
        <thead id="show">
            <table>

                <tr>
                    <td><h4>{{$nickname}}:</h4></td>
                    <td>{{$v['message']}}</td>
                </tr>

            </table>
        </thead>
    @endforeach
</div>

<form action="" class="form-inline">
    {{csrf_field()}}
    <input type="hidden" value="{{$openid}}" id="openid">
    <input type="hidden" value="1" id="msg_pos">
    <textarea name="" id="send_msg" cols="100" rows="5"></textarea>
    <input type="button" id="btn" value="发送">
</form>

<div style="float:right" id="right"></div>

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
                    if(msg=='success'){
                        $("#right").append('<h3>'+send_msg+':客服</h3>');
                        $("#send_msg").val();
                    }
                }
            )
        })


        var clear=function(){
            var openid=$('#openid').val();
            var _tr='';
            $.post(
                "massage",
                {openid:openid},
                function(msg){

                    for(var i in msg['data']){
                        _tr+=
                            "<tr>" +
                            "<td>"+"<h3>"+msg['nickname']+":"+"</h3>"+"</td>" +
                            "<td>"+msg['data'][i]['message']+"</td>" +
                            "</tr>"

                    }
                    $('#show').html(_tr);
                },'json'
            )
        };

        var a =setInterval(function(){
            clear();
        },1000*3)

    })
</script>
