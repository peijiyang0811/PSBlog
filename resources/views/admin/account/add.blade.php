@extends('layout.admin')
@section('title', '添加新用户')
@section('contents')
    <div class="am-u-sm-10 am-u-md-10 am-u-lg-10 am-u-lg-offset-1">
        <div class="widget am-cf">
            <div class="widget-head am-cf">
                <div class="widget-title am-fl">添加新用户</div>
            </div>
            <div class="widget-body am-fr">
                @if (count($errors) > 0)
                    <div class="am-alert am-alert-warning" data-am-alert>
                        <button type="button" class="am-close">&times;</button>
                        <ul>
                            @foreach($errors->all() as $item => $error)
                                <li>{{$item + 1}}.{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('error'))
                        <div class="am-alert am-alert-danger" data-am-alert>
                            <button type="button" class="am-close">&times;</button>
                            {{session('error')}}
                        </div>
                @endif
                @if (session('success'))
                        <div class="am-alert am-alert-success" data-am-alert>
                            {{session('success')}}
                        </div>
                @endif
                <form action="{{url('admin/user/addPost')}}" method="post" class="am-form tpl-form-border-form" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <div class="am-form-group am-form-icon am-form-feedback">
                        <label for="user-name" class="am-u-sm-8 am-u-lg-8 am-form-label am-text-left">用户名 <span class="tpl-form-line-small-title">nick_name</span></label>
                        <div style="float: left" class="am-form-group am-u-sm-8 am-u-lg-8 am-u-md-8">
                            <input type="text" value="{{old('user_name')}}" class="am-form-field tpl-form-input am-margin-top-xs am-radius" name="user_name" placeholder="请输入用户名">
                            <small>请填写数字或字母组合的用户名。</small>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label for="user-name" class="am-u-sm-8 am-u-lg-8 am-form-label am-text-left">真实姓名 <span class="tpl-form-line-small-title">real</span></label>
                        <div style="float: left" class="am-u-sm-8 am-u-lg-8 am-u-md-8">
                            <input type="text" value="{{old('real_name')}}" class="am-form-field tpl-form-input am-margin-top-xs am-radius" name="real_name" placeholder="请输入用户名">
                            <small>请填写汉字2-10字左右。</small>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label for="user-name" class="am-u-sm-8 am-u-lg-8 am-form-label am-text-left">邮箱 <span class="tpl-form-line-small-title">email</span></label>
                        <div style="float: left" class="am-u-sm-8 am-u-lg-8 am-u-md-8">
                            <input type="text" value="{{old('user_email')}}" class="am-form-field tpl-form-input am-margin-top-xs am-radius" name="user_email" placeholder="请输入合法的邮箱号">
                            <small>请填写正确的邮箱地址</small>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label for="user-name" class="am-u-sm-8 am-u-lg-8 am-form-label am-text-left">联系电话 <span class="tpl-form-line-small-title">mobile</span></label>
                        <div style="float: left" class="am-u-sm-8 am-u-lg-8 am-u-md-8">
                            <input type="text" class="am-form-field tpl-form-input am-margin-top-xs am-radius" value="{{old('user_phone')}}" name="user_phone" placeholder="请输入合法的手机号">
                            <small>请填写11位手机号</small>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label for="user-phone" class="am-u-sm-12 am-form-label am-text-left">用户组 <span class="tpl-form-line-small-title">group</span></label>
                        <div class="am-u-sm-12  am-margin-top-xs">
                            <select data-am-selected="{searchBox: 1}" name="rule_id" style="display: none;">
                                <option value="1" selected>普通用户</option>
                                <option value="2">管理员</option>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label for="user-weibo" class="am-u-sm-12 am-form-label  am-text-left">头像 <span class="tpl-form-line-small-title">avatar</span></label>
                        <div class="am-u-sm-12 am-margin-top-xs">
                            <div class="am-form-group am-form-file">
                                <div class="tpl-form-file-img">
                                    <img src="" alt="">
                                </div>
                                <button type="button" class="am-btn am-btn-danger am-btn-sm ">
                                    <i class="am-icon-cloud-upload"></i> 添加图片</button>
                                <input id="doc-form-file" type="file" name="avatar" multiple="">
                            </div>
                        </div>
                    </div>

                    <div class="am-form-group">
                        <div class="am-u-sm-12 am-u-sm-push-12">
                            <input type="submit" class="am-btn am-btn-primary tpl-btn-bg-color-success" value="确认添加">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $('.am-alert-warning').fadeOut(5000, 'linear');
        $('.am-alert-success').fadeOut(5000);
    </script>
@endsection