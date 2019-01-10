@extends('layouts.bst')

@section('content')
    <form class="form-signin" action="/userreg" method="post">
        {{csrf_field()}}
        <h2 class="form-signin-heading">注册</h2>
        <div style="width: 300px">
            <label for="inputEmail">name</label>
            <input type="text" name="u_name" class="form-control" required autofocus>

            <label for="inputEmail">Email</label>
            <input type="email" name="u_email" id="inputEmail" class="form-control" required autofocus>

            <label for="inputEmail">Age</label>
            <input type="text" name="u_age" class="form-control" required autofocus>

            <label for="inputPassword" >Password</label>
            <input type="password" name="u_pwd" id="inputPassword" class="form-control" required>
            <p></p>
            <div style="width: 100px">
                <button class="btn btn-info" type="submit">注册</button>
            </div>
        </div>
    </form>
@endsection
