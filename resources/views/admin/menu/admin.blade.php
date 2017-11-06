@extends('layout.admin')
@section('title', '后台菜单列表')
@section('contents')
    <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
        <div class="container-fluid am-cf">
            <div class="row">
                <div class="am-u-sm-12 am-u-md-12 am-u-lg-9">
                    <div class="page-header-heading">Admin Navigate</div>
                </div>
            </div>

        </div>
        <div id="adminNav" class="widget am-cf" >
            <div class="widget-head am-cf">
                <div class="widget-title am-fl">后台管理菜单</div>
                <div style="float: right;cursor: pointer;" id="add" class="widget-title am-fl"> 添加菜单 <small>Add New Nav</small></div>
            </div>
            <div class="widget-body  widget-body-lg am-fr">

                <table width="100%" class="am-table am-table-compact am-table-striped tpl-table-black " id="example-r">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>类型</th>
                        <th>标题</th>
                        <th>父级</th>
                        <th>链接</th>
                        <th>icon</th>
                        <th>状态</th>
                        <th>添加人</th>
                        <th>添加时间</th>
                        <th>修改人</th>
                        <th>修改时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{--even gradeC--}}
                        @foreach($list as $val)
                            <tr class="gradeX">
                                <td>{{$val -> id}}</td>
                                <td>{{$val -> type_name}}</td>
                                <td>{{$val -> title}}</td>
                                <td>{{$val -> p_name}}</td>
                                <td>{{$val -> url}}</td>
                                <td><i class="sidebar-nav-link-logo {{$val -> icon}}"></i>{{$val -> icon}}</td>
                                <td>
                                    @if ($val -> status == 1)
                                        <span class="am-badge am-badge-success am-round am-btn-xs">已启用</span> <button nav_id="{{$val -> id}}" type="button" class=" am-btn-xs am-btn am-btn-danger  am-round">关闭</button>
                                    @elseif($val -> status == 2)
                                        <span class="am-badge am-badge-danger am-round am-btn-xs">已关闭</span> <button nav_id="{{$val -> id}}" type="button" class=" am-btn-xs am-btn am-btn-success  am-round">开启</button>
                                    @endif
                                </td>
                                <td>{{$val -> admin_name}}</td>
                                <td>{{$val -> create_time}}</td>
                                <td>{{$val -> edit_name}}</td>
                                <td>{{$val -> update_time}}</td>
                                <td>
                                    <div class="tpl-table-black-operation">
                                        <a href="{{url('/admin/menu/edit/'.$val -> id)}}">
                                            <i class="am-icon-pencil"></i> 编辑
                                        </a>
                                        <a href="javascript:;" type="{{$val -> type}}" nav_id="{{$val -> id}}" class="tpl-table-black-operation-del">
                                            <i class="am-icon-trash"></i> 删除
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <span>{{$list -> links()}}</span>
            </div>
        </div>

        <div id="addNav" style="display:none" class="widget am-cf">
            <div class="widget-head am-cf">
                <div class="widget-title am-fl">添加新菜单</div>
                <div style="float: right;cursor: pointer;" id="go-back" class="widget-title am-fl"> 返回列表 <small>Go Back</small></div>
            </div>
            <div class="widget-body am-fr">
                <form class="am-form tpl-form-border-form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <div class="am-form-group">
                        <label for="user-phone" class="am-u-sm-6 am-form-label am-text-left">链接分类 <span class="tpl-form-line-small-title">group</span></label>
                        <div class="am-u-sm-12  am-margin-top-xs">
                            <select data-am-selected="{searchBox: 1}" name="pid" style="display: none;">
                                <option value="0" selected>一级列表</option>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label for="user-phone" class="am-u-sm-6 am-form-label am-text-left">类型<span class="tpl-form-line-small-title">type</span></label>
                        <div class="am-u-sm-12  am-margin-top-xs">
                            <select data-am-selected="{searchBox: 1}" name="type" style="display: none;">
                                <option value="1" selected>前台</option>
                                <option value="2">后台</option>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label for="user-name" class="am-u-sm-8 am-u-lg-8 am-form-label am-text-left">链接标题 <span class="tpl-form-line-small-title">real</span></label>
                        <div style="float: left" class="am-u-sm-8 am-u-lg-8 am-u-md-8">
                            <input required type="text" value="{{old('title')}}" class="am-form-field tpl-form-input am-margin-top-xs am-radius" name="title" placeholder="请输入菜单标题">
                            <small>请填写汉字2-6字左右。</small>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label for="user-name" class="am-u-sm-8 am-u-lg-8 am-form-label am-text-left">链接 <span class="tpl-form-line-small-title">url</span></label>
                        <div style="float: left" class="am-u-sm-8 am-u-lg-8 am-u-md-8">
                            <input required type="text" value="{{old('url')}}" class="am-form-field tpl-form-input am-margin-top-xs am-radius" name="url" placeholder="请输入菜单链接">
                            <small>请填写菜单链接</small>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label for="user-name" class="am-u-sm-8 am-u-lg-8 am-form-label am-text-left">ICON <span class="tpl-form-line-small-title">icon</span></label>
                        <div style="float: left" class="am-u-sm-8 am-u-lg-8 am-u-md-8">
                            <input type="text" class="am-form-field tpl-form-input am-margin-top-xs am-radius" value="{{old('icon')}}" name="icon" placeholder="">
                            <small>从 amaze ui 查找合适的</small>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <div class="am-u-sm-12 am-u-sm-push-12">
                            <button class="am-btn am-btn-primary tpl-btn-bg-color-success">确认添加</button>
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
        // 遍历输出一级列表
        $(function(){
            $.post('/api/getOneLinks', {}, function(json) {
                var html = '<option value="0" selected>一级列表</option>';
                $.each(json.data, function(k,v){
                    html += '<option value="'+v.id+'">--| '+v.title+'</option>';
                });
                $('select[name=pid]').html(html);
            });
        });
        $('#add').click(function(){
            // 切换显示内容框
            $('#adminNav').fadeOut(1000);
            //$('#adminNav').css('display', 'none');
            //$('#addNav').css('display', 'block');
            $('#addNav').fadeIn(500);
        });
        $('#go-back').click(function(){
            // 切换显示内容框
            //$('#adminNav').css('display', 'block');
            //$('#addNav').css('display', 'none');
            $('#adminNav').fadeIn(500);
            //$('#adminNav').css('display', 'none');
            //$('#addNav').css('display', 'block');
            $('#addNav').fadeOut(1000);
            window.location.reload();
        });
        // 通过Ajax 添加数据
        $('.tpl-form-border-form').submit(function(){
            return false;
        });
        /*添加数据*/
        $('.am-btn-primary').click(function() {
            var title = $('input[name=title]').val();
            var url = $('input[name=url]').val();
            var icon = $('input[name=icon]').val();
            var cate = $('select[name=pid]').val();
            var type = $('select[name=type]').val();
            $.ajax({
                url:"/admin/menu/addAdmin",
                data:{title:title, url:url, icon:icon, pid:cate, type:type},
                dataType:"json",
                type:"POST",
                success:function(json) {
                    if (json.code != 200) {
                        alertInfo($('#alert-modal'), '错误', json.message);
                        return false;
                    }
                    // 添加成功继续添加
                    alertInfo($('#alert-modal'), '添加成功', json.message);
                },
                error:function() {
                    alertInfo($('#alert-modal'), '网络错误', '登陆信息已失效,请返回重新登陆');
                }
            });
        });
        /*开启链接*/
        $('.am-btn-success').each(function() {
            var open = $(this);
            open.click(function(){
                var nav_id = $(this).attr('nav_id');
                $.ajax({
                    url:"/admin/menu/updateAdmin",
                    data:{navId:nav_id, type:1},
                    dataType:"json",
                    type:"POST",
                    success:function(json) {
                        if (json.code != 200) {
                            alertInfo($('#alert-modal'), '错误', json.message);
                            return false;
                        }
                        // 添加成功继续添加
                        alertInfo($('#alert-modal'), '修改成功', json.message);
                        window.setTimeout(reloadUrl(), 5000);
                    },
                    error:function() {
                        alertInfo($('#alert-modal'), '网络错误', '请返回重新登陆');
                    }
                });
            });
        });
        /*取消链接*/
        $('.am-btn-danger').each(function() {
            var close = $(this);
            close.click(function(){
                var nav_id = $(this).attr('nav_id');
                $.ajax({
                    url:"/admin/menu/updateAdmin",
                    data:{navId:nav_id, 'type':2},
                    dataType:"json",
                    type:"POST",
                    success:function(json) {
                        if (json.code != 200) {
                            alertInfo($('#alert-modal'), '错误', json.message);
                            return false;
                        }
                        // 添加成功继续添加
                        alertInfo($('#alert-modal'), '修改成功', json.message);
                        window.setTimeout(reloadUrl(), 5000);
                    },
                    error:function() {
                        alertInfo($('#alert-modal'), '网络错误', '请返回重新登陆');
                    }
                });

            });
        });
        /*删除链接*/
        $('.tpl-table-black-operation-del').each(function(){
            var del = $(this);
            del.click(function(){
                var id = del.attr('nav_id');
                if (confirm('您确定要删除此链接么?')) {
                    $.ajax({
                        url:"/admin/menu/delAdmin",
                        data:{navId:id},
                        dataType:"json",
                        type:"POST",
                        success:function(json) {
                            if (json.code != 200) {
                                alertInfo($('#alert-modal'), '错误', json.message);
                                return false;
                            }
                            del.parent().parent().parent().remove();
                        },
                        error:function() {
                            alertInfo($('#alert-modal'), '网络错误', '请返回重新登陆');
                        }
                    });
                }
            });
        });
    </script>
@endsection