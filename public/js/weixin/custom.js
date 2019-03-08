$(function () {
    $(document).on('click','#button',function(){
        $("#box2").clone().appendTo("#box");
    })
    $(document).on('click','#button1',function(){
        $("#box2").clone().appendTo("#box3");
    })
})