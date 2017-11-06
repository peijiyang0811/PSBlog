<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PS伐木累后台管理系统</title>
    <meta name="description" content="PS伐木累">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <meta name="keywords" content="index">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="apple-mobile-web-app-title" content="Amaze UI" />
    <link rel="stylesheet" href="{{asset('/admin/css/amazeui.min.css')}}" />
    <link rel="stylesheet" href="{{asset('/admin/css/amazeui.datatables.min.css')}}" />
    <link rel="stylesheet" href="{{asset('/admin/css/app.css')}}">
    <script src="{{asset('/admin/js/jquery.min.js')}}"></script>
    <script src="{{asset('/js/tools/system.js')}}"></script>
    <script src="{{asset('/js/tools/md5.js')}}"></script>
    <script src="{{asset('/js/tools/sha1.js')}}"></script>
    <script>
        //  ajax 的post验证  需要加上  meta 头部  和 ajaxSetup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
    </script>
</head>

<body data-type="login">
<script src="{{asset('/admin/js/theme.js')}}"></script>
<div class="am-g tpl-g">
    <div class="tpl-login">
        <div class="tpl-login-content">
            <div class="tpl-login-logo">
                <img src="{{asset('/images/logo/yuan2.png')}}" class="am-img-thumbnail am-circle">
            </div>
            @if(session('error'))
                <div class="am-alert am-alert-warning" data-am-alert>
                    <button type="button" class="am-close">&times;</button>
                    <p> {{session('error')}}</p>
                </div>
            @endif
            <form class="am-form tpl-form-line-form">
                <div class="am-form-group">
                    <input type="text" is_ok="0" class="tpl-form-input" id="user-name" placeholder="请输入手机号">
                </div>
                <div class="am-form-group">
                    <input type="password" is_ok="0" class="tpl-form-input" id="user-password" placeholder="请输入密码">
                </div>
                <div class="am-form-group">
                    <button type="button" class="am-btn am-btn-primary  am-btn-block tpl-btn-bg-color-success  tpl-login-btn">提交</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{--模态框--}}
<div class="am-modal am-modal-alert" tabindex="-1" id="login-alert">
    <div class="am-modal-dialog">
        <div class="am-modal-hd"></div>
        <div class="am-modal-bd">

        </div>
        <div class="am-modal-footer">
            <span class="am-modal-btn">确定</span>
        </div>
    </div>
</div>
<script src="{{asset('/admin/js/amazeui.min.js')}}"></script>
<script src="{{asset('/admin/js/app.js')}}"></script>
<script>
    /*$(window).load(function(){$.AMUI.progress.start();$.AMUI.progress.inc(0.4);});
    $(document).ready(function(){$.AMUI.progress.done();});*/
    login_validate($('#user-name'), 'phone', '用户名');
    login_validate($('#user-password'), 'password', '密码');
    $('.tpl-login-btn').click(function(){
        if ($('#user-name').attr('is_ok') == 1 && $('#user-password').attr('is_ok') == 1) {
            // 开始执行ajax请求
            $('.tpl-login-btn').removeAttr('disabled');
            var user = $('#user-name').val();
            var pass = $('#user-password').val();
            pass = $.md5($.sha1($.md5(pass)) + pass_hash);
            $.post('/admin/api/check', {user:user,pass:pass}, function(json){
                if (json.code != 200){
                    alertInfo($('#login-alert'), '错误', json.message);
                    return;
                }
                loading();
                window.setTimeout("window.location='/admin/index'",3000);
            });

        } else {
            alertInfo($('#login-alert'), '错误', '请检查是否输入正确');
        }
    });
    // 按 enter 进入
    $(window).keydown(function(event){
        switch(event.keyCode) {
            case 13:
                if ($('#user-name').attr('is_ok') == 1 && $('#user-password').attr('is_ok') == 1) {
                    // 开始执行ajax请求
                    $('.tpl-login-btn').removeAttr('disabled');
                    var user = $('#user-name').val();
                    var pass = $('#user-password').val();
                    pass = $.md5($.sha1($.md5(pass)) + pass_hash);
                    $.post('/admin/api/check', {user:user,pass:pass}, function(json){
                        if (json.code != 200){
                            alertInfo($('#login-alert'), '错误', json.message);
                            return;
                        }
                        loading();
                        window.setTimeout("window.location='/admin/index'",3000);
                    });

                } else {
                    alertInfo($('#login-alert'), '错误', '请检查是否输入正确');
                }
                break;
        }
    });
</script>
</body>

</html>