@extends('layout.admin')
@section('title', '友情链接列表')
@section('contents')
    <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
        <div class="widget am-cf">
            <div class="widget-head am-cf">
                <div class="widget-title am-fl">友情链接列表</div>
                {{--<div class="widget-function am-fr">
                    <a href="javascript:;" class="am-icon-cog"></a>
                </div>--}}
            </div>
            <div class="widget-body  widget-body-lg am-fr">

                <table width="100%" class="am-table am-table-compact am-table-bordered am-table-radius am-table-striped tpl-table-black " id="example-r">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>站点</th>
                        <th>站长</th>
                        <th>友链</th>
                        <th>Logo</th>
                        <th>联系电话</th>
                        <th>联系QQ</th>
                        <th>状态</th>
                        <th>添加时间</th>
                        <th>操作人</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($links as $link)
                            <tr class="gradeX">
                                <td>{{$link -> id}}</td>
                                <td>{{$link -> title}}</td>
                                <td>{{$link -> contact_name}}</td>
                                <td>{{$link -> site_url}}</td>
                                <td><img src="{{asset($link -> site_logo)}}" class="tpl-table-line-img" alt=""></td>
                                <td>{{$link -> contact_phone}}</td>
                                <td>{{$link -> contact_qq}}</td>
                                <td>
                                    @if ($link -> status == 1)
                                        <span class="am-badge am-badge-success am-round am-text-sm">正常</span>
                                    @elseif($link -> status == 2)
                                        <span class="am-badge am-badge-warning am-round am-text-sm">禁用</span>
                                    @endif
                                </td>
                                <td>{{$link -> create_time}}</td>
                                <td>{{$link -> real_name}}</td>
                                <td>
                                    <div class="tpl-table-black-operation">
                                        <a href="{{url('/admin/links/edit/'.$link->id)}}" class="am-btn am-btn-default am-radius">
                                            <i class="am-icon-pencil"></i> 编辑
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <span>{{$links -> links()}}</span>
            </div>
        </div>
    </div>
    {{--模态框--}}
    <div class="am-modal am-modal-alert" tabindex="-1" id="del-alert">
        <div class="am-modal-dialog">
            <div class="am-modal-hd"></div>
            <div class="am-modal-bd">

            </div>
            <div class="am-modal-footer">
                <span class="am-modal-btn">确定</span>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        // 删除数据
        $('.tpl-table-black-operation-del').each(function() {
            var thisDel = $(this);
            thisDel.click(function(){
                if (confirm('您确定要删除么?')) {
                    var id = thisDel.attr('user_id');
                    $.post('/admin/user/delete', {id:id}, function(json) {
                        if (json.code != 200) {
                            alertInfo($('#del-alert'), '错误', json.message);
                            return;
                        }
                        // 开始删除该链接对应的 tr 标签
                        thisDel.parent().parent().parent().remove();
                    });
                }

            });
        });
    </script>
@endsection
