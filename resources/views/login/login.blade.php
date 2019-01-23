{{-- 用户登录--}}

@extends('layouts.bst')

@section('content')
    <form class="form-signin" action="/dologin1" method="post">
        {{csrf_field()}}
        <div style="width: 300px">
            <label for="inputEmail">昵称</label>
            <input type="text" name="name" id="inputName" class="form-control" required autofocus>

            <label for="inputPassword" >密码</label>
            <input type="password" name="pwd" id="inputPassword" class="form-control" required>
            <p></p>
            <div style="width: 100px">
                <button class="btn btn-info" type="submit">登录</button>
            </div>
        </div>
    </form>
@endsection



