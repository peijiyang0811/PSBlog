<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>@yield('title') -- PSBLOG</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="icon" type="image/png" href="{{asset('/favicon.ico')}}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Amaze UI"/>
    <meta name="msapplication-TileColor" content="#0e90d2">
    <link rel="stylesheet" href="{{asset('/home/css/amazeui.min.css')}}">
    <link rel="stylesheet" href="{{asset('/home/css/app.css')}}">
    <link rel="stylesheet" href="{{asset('/css/returnTop.css')}}">
    {{--<link rel="stylesheet" href="{{asset('/markdown/dist/simplemde-theme-base.min.css')}}">
    <link rel="stylesheet" href="{{asset('/markdown/dist/font-awesome.min.css')}}">--}}
    {{--<link rel="stylesheet" href="{{asset('/markdown/dist/github-markdown.css')}}">--}}
    <link rel="stylesheet" href="{{asset('/editorMd/css/editormd.min.css')}}">
    <link rel="stylesheet" href="{{asset('/prism/prism.css')}}">
</head>

<body id="blog">
<header class="am-g am-g-fixed blog-fixed blog-text-center blog-header">
    <div class="am-u-sm-4 am-u-sm-centered">
        <img width="200" src="{{asset('/images/logo/header_logo.png')}}"/>
        <br>
    </div>
    <div class="am-u-sm-6 am-u-lg-3">
        &nbsp;&nbsp;<span class="am-icon-clock-o"></span>
    </div>
    <div class="am-u-sm-3">
        @if (session('home_user_id'))
            <?php $user = json_decode(session('home_user_id'))?>
            <span class="am-icon-user"> &nbsp;<a href="{{url('/center/person')}}" class="blog-color">{{$user -> user_name}}</a></span>
            &nbsp;&nbsp;&nbsp;
            <span class="am-icon-sign-out"> &nbsp;<a href="{{url('/out')}}">退出</a></span>
        @else
            <span class="am-icon-user">&nbsp;&nbsp; <a href="{{url('/login')}}">登陆</a></span>
        @endif
    </div>
</header>
<hr>
<nav class="am-g am-g-fixed blog-fixed blog-nav">
    <button style="border-color: #3bb4f2" class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only blog-button" data-am-collapse="{target: '#blog-collapse'}" ><span class="am-sr-only">导航切换</span> <span class="am-icon-bars"></span></button>

    <div class="am-collapse am-topbar-collapse" id="blog-collapse">
        <ul id="home_nav" class="am-nav am-nav-pills am-topbar-nav">

        </ul>
        <form class="am-topbar-form am-topbar-right am-form-inline" onsubmit="return false" role="search">
            <div class="am-form-group">
                <input name="works" style="width: 300px" type="text" class="am-form-field am-input-sm" placeholder="搜索">
            </div>
        </form>
    </div>
</nav>
<hr>

<!-- content srart -->
<div class="am-g am-g-fixed blog-fixed">
    @section('contents')

    @show
</div>
<!-- content end -->
<div style="right: 10px;">
    <a href="#top" title="回到顶部" class="am-icon-btn am-icon-arrow-up am-active" id="back-to-top"></a>
</div>
{{--模态框--}}
<div class="am-modal am-modal-alert" tabindex="-1" id="alert-modal">
    <div class="am-modal-dialog">
        <div class="am-modal-hd"></div>
        <div class="am-modal-bd">

        </div>
        <div class="am-modal-footer">
            <span class="am-modal-btn">确定</span>
        </div>
    </div>
</div>
@include('layout.home_foot')

<!--[if (gte IE 9)|!(IE)]><!-->
<script src="{{asset('/home//js/jquery.min.js')}}"></script>
<script src="{{asset('/js/tools/timer.js')}}"></script>
<script src="{{asset('/js/tools/system.js')}}"></script>
<script src="{{asset('/prism/prism.js')}}"></script>
{{--<script src="{{asset('/markdown/dist/simplemde.min.js')}}"></script>--}}
<script src="{{asset('/editorMd/editormd.min.js')}}"></script>
<!--<![endif]-->
<!--[if lte IE 8 ]>
<script src="http://libs.baidu.com/jquery/1.11.3/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="{{asset('/home/js/amazeui.ie8polyfill.min.js')}}"></script>
<![endif]-->
<script src="{{asset('/home/js/amazeui.min.js')}}"></script>
<script src="{{asset('/js/global.js')}}"></script>
<script src="{{asset('/js/returnTop.js')}}"></script>
<!-- <script src="{{asset('/home/js/app.js')}}"></script> -->
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    var curr_url = document.location.href;
    var url = window.location.protocol +'//'+window.location.host;
    $(function(){
        $.ajax({
            url:'/api/getAdminLinks',
            type:"POST",
            dataType:"json",
            data:{type:1},
            success:function(json){
                if (json.code == 200) {
                    var html = '';
                    $.each(json.data, function(k, v){
                        if (url+v.url == url+'/') {
                            html += '<li class="am-active"><a class="links" href="'+url+v.url+'">'+v.title+'</a></li>';
                        } else {
                            html += '<li><a class="links" href="'+url+v.url+'">'+v.title+'</a></li>';
                        }
                    });
                    $('#home_nav').html(html);
                }
            },
            async: false// 设置同步方法
        });
        $('.links').each(function(){
            var this_a = $(this);
            // 获取当前链接的url
            var this_url = this_a.attr('href');
            // 判断当前 href 是不是 当前链接
            if (curr_url.indexOf(this_url) != -1) {
                // 是当前url 所在的链接
                this_a.parent().addClass('am-active');
                this_a.parent().siblings().removeClass('am-active');
            }
        });
        // 个人中心
        $('.am-list li a').each(function(){
            var this_a = $(this);
            // 获取当前链接的url
            var this_url = this_a.attr('href');
            // 判断当前 href 是不是 当前链接
            if (curr_url.indexOf(this_url) != -1) {
                // 是当前url 所在的链接
                this_a.addClass('am-active');
                this_a.siblings().removeClass('am-active');
            }
        });
    });
    // 按 enter 进入
    $(window).keydown(function(event){
        switch(event.keyCode) {
            case 13:
                var words = $('input[name=works]').val();
                if (is_empty(words)) {
                    if (curr_url != url + '/center/blog/add') {
                        alert('搜索内容不能为空');
                        return;
                        redirect('/search/'+ words);
                    }
                }
                break;
            default:
                // alertInfo($('.alert-modal'), '错误', '非法操作');
                break;
        }
    });
</script>
@section('js')

@show

</body>
</html>
