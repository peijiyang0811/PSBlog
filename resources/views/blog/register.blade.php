<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <meta name="description" content="psfmaily blog">
    <meta name="keywords" content="blog">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>注册 -- PSFAMILY</title>
    <meta name="_token" content="{{ csrf_token() }}"/>
    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">

    <!-- No Baidu Siteapp-->
    <meta http-equiv="Cache-Control" content="no-siteapp"/>

    <link rel="icon" type="image/png" href="{{asset('/favicon.ico')}}">

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="PSFMAILY BLOG"/>

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="{{asset('/favicon.icon')}}">
    <meta name="msapplication-TileColor" content="#0e90d2">
    <link rel="stylesheet" href="{{asset('/home/css/amazeui.min.css')}}">
    <link rel="stylesheet" href="{{asset('/home/css/app.css')}}">
</head>
<body>
<header>
    <div class="log-header">
        <h1><a href="/">PSFAMILY</a> </h1>
    </div>
</header>

<div class="log">
    <div class="am-g">
        <div class="am-u-lg-3 am-u-md-6 am-u-sm-8 am-u-sm-centered log-content">
            <h1 class="log-title am-animation-slide-top">PSFAMILY</h1>
            <br>
            <form class="am-form" id="log-form">
                <div class="am-input-group am-radius am-animation-slide-left">
                    <input type="email" id="doc-vld-email-2-1" class="am-radius" data-validation-message="请输入正确邮箱地址" placeholder="请输入正确邮箱地址" required/>
                    <span class="am-input-group-label log-icon am-radius"><i class="am-icon-user am-icon-sm am-icon-fw"></i></span>
                </div>
                <br>
                <div class="am-input-group am-animation-slide-left log-animation-delay">
                    <input name="pwd" type="password" class="am-form-field am-radius log-input" placeholder="请输入6-18位密码" maxlength="18" required>
                    <span class="am-input-group-label log-icon am-radius"><i class="am-icon-lock am-icon-sm am-icon-fw"></i></span>

                </div>
                <br>
                <div class="am-input-group am-animation-slide-left log-animation-delay">
                    <input name="repwd" type="password" class="am-form-field am-radius log-input" placeholder="再次输入密码" maxlength="18" required>
                    <span class="am-input-group-label log-icon am-radius"><i class="am-icon-lock am-icon-sm am-icon-fw"></i></span>
                </div>
                <br>
                <button type="button" class="am-btn am-btn-primary am-btn-block am-btn-lg am-radius am-animation-slide-bottom log-animation-delay">注 册</button>
                <p class="am-animation-slide-bottom log-animation-delay"><a href="{{url('/login')}}">登陆</a></p>
                <div class="am-btn-group  am-animation-slide-bottom log-animation-delay-b">

                    {{--
                    <p>使用第三方登录</p>
                    <a href="#" class="am-btn am-btn-secondary am-btn-sm"><i class="am-icon-github am-icon-sm"></i> Github</a>
                    <a href="#" class="am-btn am-btn-success am-btn-sm"><i class="am-icon-google-plus-square am-icon-sm"></i> Google+</a>
                    <a href="#" class="am-btn am-btn-primary am-btn-sm"><i class="am-icon-stack-overflow am-icon-sm"></i> stackOverflow</a>--}}
                </div>
            </form>
        </div>
    </div>
    {{--模态框--}}
    <div class="am-modal am-modal-alert" tabindex="-1" id="login-alert">
        <div class="am-modal-dialog">
            <div class="am-modal-hd"></div>
            <div class="am-modal-bd" style="color: #1f2224">

            </div>
            <div class="am-modal-footer">
                <span class="am-modal-btn">确定</span>
            </div>
        </div>
    </div>
    <footer class="log-footer">
        © 2017 psfmaily.cn 版权所有 ICP证：<a href="http://www.miitbeian.gov.cn/" target="_blank">豫ICP备170370072号-1</a> 联系邮箱：peijiyang@psfmaily.cn
    </footer>
</div>



<!--[if (gte IE 9)|!(IE)]><!-->
<script src="{{asset('/home/js/jquery.min.js')}}"></script>
<!--<![endif]-->
<!--[if lte IE 8 ]>
<script src="http://libs.baidu.com/jquery/1.11.3/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="assets/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->
<script src="{{asset('/home/js/amazeui.min.js')}}"></script>
<script src="{{asset('/home/js/app.js')}}"></script>
<script src="{{asset('/js/tools/validata.js')}}"></script>
<script src="{{asset('/js/tools/system.js')}}"></script>
<script src="{{asset('/js/tools/md5.js')}}"></script>
<script src="{{asset('/js/tools/sha1.js')}}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $('button[type=button]').click(function(){
        // 账号 密码
        var account = $('input[type=email]').val();
        var pwd = $('input[name=pwd]').val();
        var repwd = $('input[name=repwd]').val();
        if (!validate('email', account)) {
            var dang = '<div class="log-alert am-alert am-alert-danger am-radius" style="">请输入正确邮箱地址</div>';
            $('input[type=email]').parent().append(dang);
            return;
        }
        if (!validate('password', pwd)) {
            var dang = '<div class="log-alert am-alert am-alert-danger am-radius" style="">请输入6-18位密码</div>';
            $('input[name=pwd]').parent().append(dang);
            return;
        }
        if (pwd != repwd) {
            var dang = '<div class="log-alert am-alert am-alert-danger am-radius" style="">两次输入的密码不一致</div>';
            $('input[name=repwd]').parent().append(dang);
            return;
        }
        pwd = $.md5($.sha1($.md5(pwd)) + pass_hash);
        repwd = $.md5($.sha1($.md5(repwd)) + pass_hash);
        $.post('/api/register', {email:account, password:pwd, rePwd:repwd}, function(json){
            if (json.code != 200) {
                console.log(json);
                alertInfo($('#login-alert'), '错误', json.message);
                return;
            }
            loading();
            window.setTimeout("window.location='/center/person'",3000);
        });
    });
</script>
</body>
</html>