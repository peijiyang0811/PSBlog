@extends('layout.admin')
@section('title', '编辑菜单')
@section('contents')
    <div class="am-u-sm-10 am-u-md-10 am-u-lg-10 am-u-lg-offset-1">
        <div class="widget am-cf">
            <div class="widget-head am-cf">
                <div class="widget-title am-fl">编辑菜单</div>
                <div class="widget-function am-fr">
                    <a href="{{url('admin/menu/admin')}}" class="am-icon-reply"> 返回列表</a>
                </div>
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
                <form action="{{url('admin/menu/update')}}" method="post" class="am-form tpl-form-border-form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="id" value="{{ $links -> id }}" />
                    <div class="am-form-group am-form-icon am-form-feedback">
                        <label for="links-name" class="am-u-sm-8 am-u-lg-8 am-form-label am-text-left">名称 <span class="tpl-form-line-small-title">title</span></label>
                        <div style="float: left" class="am-form-group am-u-sm-8 am-u-lg-8 am-u-md-8">
                            <input required type="text" value="{{$links -> title}}" class="am-form-field tpl-form-input am-margin-top-xs am-radius" name="title" placeholder="">
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label for="links-name" class="am-u-sm-8 am-u-lg-8 am-form-label am-text-left">Icon <span class="tpl-form-line-small-title"><i class="{{$links -> icon}}"></i></span></label>
                        <div style="float: left" class="am-u-sm-8 am-u-lg-8 am-u-md-8">
                            <input required type="text" value="{{$links -> icon}}" class="am-form-field tpl-form-input am-margin-top-xs am-radius" name="icon" placeholder="">
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label for="links-name" class="am-u-sm-8 am-u-lg-8 am-form-label am-text-left">链接 <span class="tpl-form-line-small-title">url</span></label>
                        <div style="float: left" class="am-u-sm-8 am-u-lg-8 am-u-md-8">
                            <input required type="text" value="{{$links -> url}}" class="am-form-field tpl-form-input am-margin-top-xs am-radius" name="url" placeholder="">
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label for="links-phone" class="am-u-sm-12 am-form-label am-text-left">分类 <span class="tpl-form-line-small-title">type</span></label>
                        <div class="am-u-sm-12  am-margin-top-xs">
                            <select data-am-selected="{searchBox: 1}" name="type" style="display: none;">
                                <option name="type" value="1" @if ($links->type == 1) selected @endif>前台</option>
                                <option name="type" value="2" @if ($links->type == 2) selected @endif>后台</option>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label for="links-phone" class="am-u-sm-12 am-form-label am-text-left">父级分类 <span class="tpl-form-line-small-title">pid</span></label>
                        <div class="am-u-sm-12  am-margin-top-xs">
                            <select data-am-selected="{searchBox: 1}" name="pid" style="display: none;">

                            </select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <div class="am-u-sm-12 am-u-sm-push-12">
                            <input type="submit" class="am-btn am-btn-primary tpl-btn-bg-color-success" value="确认修改">
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
        $(function(){
            $.post('/api/getOneLinks', {}, function(json) {
                var pid = {{$links->pid}};
                var html = '<option value="0" selected>一级列表</option>';
                $.each(json.data, function(k,v){
                    if (v.id == pid) {
                        html += '<option value="'+v.id+'" selected>--| '+v.title+'</option>';
                    } else {
                        html += '<option value="'+v.id+'">--| '+v.title+'</option>';
                    }
                });
                $('select[name=pid]').html(html);
            });
        });
    </script>
@endsection
