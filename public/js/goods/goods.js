$("#add_cart_btn").click(function (e) {
    e.preventDefault();
    var goods_num  = $("#goods_num").val();
    var goods_id = $("#goods_id").val();

    $.ajax({
        header:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url:'/cartadd',
        type:'post',
        data:{goods_id : goods_id,goods_num:goods_num},
        dataType:   'json',
        success: function (res) {

          //console.log(res);
            if(res.error==301){
                window.location.href=res.url;
            }else if(res.error==0){
                alert(res.msg);
                    window.location.href='/cart';

            }
        }
    })
})