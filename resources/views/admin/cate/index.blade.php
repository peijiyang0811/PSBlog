@extends('layout.admin')
@section('title', '文章分类列表')

@section('contents')
    <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
        <div class="container-fluid am-cf">
            <div class="row">
                <div class="am-u-sm-12 am-u-md-12 am-u-lg-9">
                    <div class="page-header-heading">文章分类列表</div>
                </div>
            </div>
        </div>
        <div class="widget am-cf">
            <div class="widget-head am-cf">
                <div class="widget-title am-fl"></div>
                <div class="widget-function am-fr">

                </div>
            </div>
            <div class="widget-body  widget-body-lg am-fr">
                @if (session('success'))
                    <div class="am-alert am-alert-success" data-am-alert>
                        {{session('success')}}
                    </div>
                @endif
                <table width="100%" class="am-table am-table-compact am-table-striped tpl-table-black " id="example-r">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>标题</th>
                            <th>父级</th>
                            <th>路径</th>
                            <th>状态</th>
                            <th>添加人</th>
                            <th>添加时间</th>
                            <th>修改时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $val)
                            <tr class="gradeX">
                                <td>{{$val -> id}}</td>
                                <td>{{$val -> title}}</td>
                                <td>{{$val -> p_name}}</td>
                                <td>{{$val -> path}}</td>
                                <td>
                                    @if ($val -> status == 1)
                                        <span class="am-badge am-badge-success am-round am-btn-xs">已启用</span>
                                    @elseif($val -> status == 2)
                                        <span class="am-badge am-badge-danger am-round am-btn-xs">已关闭</span>
                                    @endif
                                </td>
                                <td>{{$val -> admin_name}}</td>
                                <td>{{$val -> create_time}}</td>
                                <td>{{$val -> update_time}}</td>
                                <td>
                                    <div class="tpl-table-black-operation">
                                        <a href="{{url('/admin/cate/list/edit/'.$val -> id)}}">
                                            <i class="am-icon-pencil"></i> 编辑
                                        </a>
                                        <a link_id="{{$val -> id}}" href="javascript:;" class="tpl-table-black-operation-del">
                                            <i class="am-icon-trash"></i> 删除
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{$link -> links()}}
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $('.tpl-table-black-operation-del').each(function(){
            var this_del = $(this);
            this_del.click(function(){
                if (confirm('您确定要删除该分类么?')) {
                    var link_id = this_del.attr('link_id');
                    $.post('/admin/cate/del', {id:link_id}, function(json){
                        if (json.code != 200) {
                            alertInfo($('#alert-modal'), '错误', json.message);
                            return;
                        }
                        this_del.parent().parent().parent().remove();
                    });
                }
            });
        });
    </script>
@endsection
