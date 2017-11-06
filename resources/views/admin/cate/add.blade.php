@extends('layout.admin')
@section('title', '添加新的文章分类')

@section('contents')
    <div class="am-u-sm-12 am-u-md-10 am-u-md-offset-1 am-u-lg-10 am-u-lg-offset-1">
        <div class="container-fluid am-cf">
            <div class="row">
                <div class="am-u-sm-12 am-u-md-12 am-u-lg-9">
                    <div class="page-header-heading">添加新分类</div>
                </div>
            </div>
        </div>
        <div class="widget am-cf">
            <div class="widget-head am-cf">
                <div class="widget-title am-fl"></div>
                <div class="widget-function am-fr">

                </div>
            </div>
            <div class="widget-body am-fr">
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
                <form class="am-form tpl-form-border-form" method="post" action="{{url('/admin/cate/insert')}}">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="am-form-group">
                        <label for="user-name" class="am-u-sm-12 am-form-label am-text-left">分类名称 <span class="tpl-form-line-small-title">Title</span></label>
                        <div class="am-u-sm-12">
                            <input required type="text" name="title" class="tpl-form-input am-margin-top-xs" id="user-name" placeholder="请输入分类名称">
                            <small>请填写标题,3-10个字符左右。</small>
                        </div>
                    </div>

                    <div class="am-form-group">
                        <label for="user-phone" class="am-u-sm-12 am-form-label am-text-left">所属分类 <span class="tpl-form-line-small-title">cate_id</span></label>
                        <div class="am-u-sm-12  am-margin-top-xs">
                            <select data-am-selected="{searchBox: 1}" name="pid" style="display: none;">
                                <option value="0">一级分类</option>
                                @foreach($cate as $key => $val)
                                    <option value="{{$val -> id}}">{{$val->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="am-form-group">
                        <label for="user-intro" class="am-u-sm-12 am-form-label  am-text-left">是否启用</label>
                        <label class="am-radio-inline">
                            <input type="radio" name="status" value="1"  class="am-ucheck-radio" checked>
                            <span class="am-ucheck-icons"><i class="am-icon-unchecked"></i><i class="am-icon-checked"></i></span> 启用
                        </label>
                        <label class="am-radio-inline">
                            <input type="radio" name="status" value="2"  class="am-ucheck-radio">
                            <span class="am-ucheck-icons"><i class="am-icon-unchecked"></i><i class="am-icon-checked"></i></span> 关闭
                        </label>
                    </div>

                    <div class="am-form-group">
                        <div class="am-u-sm-12 am-u-sm-push-12">
                            <button type="submit" class="am-btn am-btn-primary tpl-btn-bg-color-success ">提交</button>
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