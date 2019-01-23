@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">文件上传</div>

                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="/uploads">
                            {{ csrf_field() }}
                            <input type="file" name="upload"><input type="submit" value="UPLOAD">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
