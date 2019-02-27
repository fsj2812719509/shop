<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">

    <title>BootStrap</title>

   {{-- <link rel="stylesheet" href="{{URL::asset('/bootstrap/css/bootstrap.min.css')}}">--}}
    <link rel="stylesheet" href="http://zty.tactshan.com/bootstrap/css/bootstrap.min.css"/>
</head>
<body>

<div class="container">
    <!-- Static navbar  -->
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

            </div>
            <!--/.nav-collapse -->
        </div><!--/.container-fluid -->
    </nav>
    @yield('content')
</div>

@section('footer')

    <script src="{{URL::asset('/js/jquery-3.2.1.min.js')}}"></script>
    {{--<script src="http://zty.tactshan.com/js/jquery-3.2.1.min.js"/>--}}
    <script src="{{URL::asset('/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('/bootstrap/js/jquery.qrcode.min.js')}}"></script>
   {{--<script src="http://zty.tactshan.com/bootstrap/js/bootstrap.min.js"/--}}

@show
</body>
</html>