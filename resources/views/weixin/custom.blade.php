@extends('layouts.bst')

@section('content')
    <div class="container">
        <form class="form-inline">
            {{csrf_field()}}
            <input type="button" value="一级按钮">&nbsp;名字: <input type="text" id="name">&nbsp;<input type="button" value="克隆">
            <br><br>
            <input type="button" value="二级按钮">&nbsp;&nbsp;<input type="button" value="克隆">
            <br>
            按钮类型 <select>
                        <option value="">1</option>
                    </select>
            <br>
            二级按钮名字 <input type="text" id="name2">
            <br>
            二级按钮url <input type="text" id="url">
            二级按钮名字key <input type="text" id="key">
        </form>
    </div>
@endsection
@section('footer')
    @parent
   {{-- <script src="{{URL::asset('/js/weixin/chat.js')}}"></script>--}}
@endsection