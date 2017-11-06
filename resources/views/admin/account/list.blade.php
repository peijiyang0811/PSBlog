@extends('layout.admin')
@section('title', '用户列表')
@section('contents')
    <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
        <div class="widget am-cf">
            <div class="widget-head am-cf">
                <div class="widget-title am-fl">用户列表</div>
                {{--<div class="widget-function am-fr">
                    <a href="javascript:;" class="am-icon-cog"></a>
                </div>--}}
            </div>
            <div class="widget-body  widget-body-lg am-fr">

                <table width="100%" class="am-table am-table-compact am-table-bordered am-table-radius am-table-striped tpl-table-black " id="example-r">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>头像</th>
                        <th>昵称</th>
                        <th>真实姓名</th>
                        <th>权限</th>
                        <th>联系电话</th>
                        <th>邮箱</th>
                        <th>状态</th>
                        <th>注册时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="gradeX">
                                <td>{{$user -> id}}</td>
                                <td><img src="{{asset($user -> user_avatar)}}" class="tpl-table-line-img" alt=""></td>
                                <td>{{$user -> user_name}}</td>
                                <td>{{$user -> real_name}}</td>
                                <td><span class="am-badge am-badge-success am-text-sm">{{$user -> rules}}</span></td>
                                <td>{{$user -> user_phone}}</td>
                                <td>{{$user -> user_email}}</td>
                                <td>
                                    @if ($user -> status == 1)
                                        <span class="am-badge am-badge-success am-round am-text-sm">正常</span>
                                    @elseif($user -> status == 2)
                                        <span class="am-badge am-badge-warning am-round am-text-sm">禁言</span>
                                    @elseif($user -> status == 99)
                                        <span class="am-badge am-badge-danger am-round am-text-sm">封禁</span>
                                    @endif
                                </td>
                                <td>{{$user -> create_time}}</td>
                                <td>
                                    <div class="tpl-table-black-operation">
                                        <a href="{{url('/admin/user/edit/'.$user->id)}}" class="am-btn am-btn-default am-radius">
                                            <i class="am-icon-pencil"></i> 编辑
                                        </a>
                                        <a href="javascript:;" user_id="{{$user->id}}" class="tpl-table-black-operation-del">
                                            <i class="am-icon-trash"></i> 删除
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <span>{{$users -> links()}}</span>
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