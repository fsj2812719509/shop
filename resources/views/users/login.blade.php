{{-- 用户登录--}}

@extends('layouts.bst')

@section('content')
    <form class="form-signin" action="/userlogin" method="post">
        {{csrf_field()}}
        <h2 class="form-signin-heading">请登录</h2>
        <div style="width: 300px">
            <label for="inputEmail">name</label>
            <input type="text" name="name" id="inputEmail" class="form-control" required autofocus>

            <label for="inputPassword" >Password</label>
            <input type="password" name="pwd" id="inputPassword" class="form-control" required>
            <p></p>
            <div style="width: 100px">
                <button class="btn btn-info" type="submit">登录</button>
            </div>
        </div>
    </form>
@endsection