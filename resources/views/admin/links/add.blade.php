@extends('layout.admin')
@section('title', '添加新友链')
@section('contents')
    <div class="am-u-sm-10 am-u-md-10 am-u-lg-10 am-u-lg-offset-1">
        <div class="widget am-cf">
            <div class="widget-head am-cf">
                <div class="widget-title am-fl">添加新友链</div>
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

                <form action="{{url('admin/links/new')}}" method="post" class="am-form tpl-form-border-form" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <div class="am-form-group am-form-icon am-form-feedback">
                        <label for="user-name" class="am-u-sm-8 am-u-lg-8 am-form-label am-text-left">友链名称 <span class="tpl-form-line-small-title">title</span></label>
                        <div style="float: left" class="am-form-group am-u-sm-8 am-u-lg-8 am-u-md-8">
                            <input type="text" value="{{old('title')}}" class="am-form-field tpl-form-input am-margin-top-xs am-radius" name="title" placeholder="">
                            <small>请填写友情链接名称。</small>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label for="user-name" class="am-u-sm-8 am-u-lg-8 am-form-label am-text-left">友链URL <span class="tpl-form-line-small-title">Url</span></label>
                        <div style="float: left" class="am-u-sm-8 am-u-lg-8 am-u-md-8">
                            <input type="text" value="{{old('site_url')}}" class="am-form-field tpl-form-input am-margin-top-xs am-radius" name="site_url" placeholder="">
                            <small>请填写正确URL格式。</small>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label for="user-name" class="am-u-sm-8 am-u-lg-8 am-form-label am-text-left">联系人 <span class="tpl-form-line-small-title">name</span></label>
                        <div style="float: left" class="am-u-sm-8 am-u-lg-8 am-u-md-8">
                            <input type="text" value="{{old('contact_name')}}" class="am-form-field tpl-form-input am-margin-top-xs am-radius" name="contact_name" placeholder="">
                            <small>请填写汉字</small>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label for="user-name" class="am-u-sm-8 am-u-lg-8 am-form-label am-text-left">QQ <span class="tpl-form-line-small-title"></span></label>
                        <div style="float: left" class="am-u-sm-8 am-u-lg-8 am-u-md-8">
                            <input type="text" value="{{old('contact_qq')}}" class="am-form-field tpl-form-input am-margin-top-xs am-radius" name="contact_qq" placeholder="">
                            <small>请填写正确的QQ号码</small>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label for="user-name" class="am-u-sm-8 am-u-lg-8 am-form-label am-text-left">联系电话 <span class="tpl-form-line-small-title">phone</span></label>
                        <div style="float: left" class="am-u-sm-8 am-u-lg-8 am-u-md-8">
                            <input type="text" value="{{old('contact_phone')}}" class="am-form-field tpl-form-input am-margin-top-xs am-radius" name="contact_phone" placeholder="">
                            <small>请填写正确的手机号</small>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label for="user-weibo" class="am-u-sm-12 am-form-label  am-text-left">Logo <span class="tpl-form-line-small-title"></span></label>
                        <div class="am-u-sm-12 am-margin-top-xs">
                            <div class="am-form-group am-form-file">
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
