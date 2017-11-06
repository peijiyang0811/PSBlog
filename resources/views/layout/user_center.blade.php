@extends('layout.home')

@section('contents')
    <br>
    <div class="am-u-sm-4 am-u-md-4 am-u-lg-4">
        @include('layout.user_center_nav')
    </div>
    {{--内容部分--}}
    <div class="am-u-sm-8 am-u-md-8 am-u-lg-8">

        @section('user_center')

        @show()
    </div>
@endsection
@section('js')
    <script>

    </script>
@endsection