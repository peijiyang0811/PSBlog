@extends('layout.user_center')
@section('title', '创建博客')

@section('user_center')
    <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
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
                @if (session('success'))
                    <div class="am-alert am-alert-success" data-am-alert>
                        {{session('success')}}
                    </div>
                @endif
                {{-- method="post" action="{{url('/center/blog/add_blog')}}"--}}
                <form id="mark-blog" class="am-form am-form-horizontal" method="post" action="{{url('/center/blog/add_blog')}}" enctype="multipart/form-data">
                    <fieldset>
                        <legend>创建博文</legend>
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="am-form-group">
                            <label for="doc-ipt-3-1" class="am-form-label">标题</label>
                            <p><input required type="text" name="title" class="am-form-field am-radius" placeholder="请输入标题" /></p>
                        </div>
                        <div class="am-form-group">
                            <label for="doc-ipt-3-1" class="am-form-label">副标题</label>
                            <p><input required type="text" name="subtitle" class="am-form-field am-radius" placeholder="请输入副标题" /></p>
                        </div>
                        <div class="am-form-group am-form-file">
                            <label for="doc-ipt-file-2">封面图片</label>
                            <div>
                                <button type="button" class="am-btn am-btn-default am-btn-sm">
                                    <i class="am-icon-cloud-upload"></i> 选择要上传的文件</button>
                            </div>
                            <input type="file" name="head_image" id="doc-ipt-file-2">
                        </div>
                        <div class="am-form-group am-form-select">
                            <label for="doc-select-1">分类</label>
                            <select class="doc-select-1" name="cate_id">
                                @foreach($cate as $val)
                                    <option value="{{$val -> id}}">{{$val -> title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="am-form-group">
                            <label for="doc-select-1">是否展示文章</label><br>
                            <label class="am-radio-inline">
                                <input type="radio" checked  value="1" name="is_open"> 显示
                            </label>
                            <label class="am-radio-inline">
                                <input type="radio" value="2" name="is_open"> 隐藏
                            </label>
                        </div>

                        <div class="am-form-group">
                            <label for="doc-ta-1">正文</label>
                            <textarea name="markdown" style="display: none" id="doc-ta-1"></textarea>
                        </div>
                        <p><button type="submit" class="am-btn am-btn-default">提交</button></p>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script>

        var mark = new SimpleMDE({
            element: document.getElementById("doc-ta-1"),
            autoDownloadFontAwesome: false,
            placeholder: "Type here...",
            autosave: {
                enabled: true,
                unique_id: "doc-ta-1"
            }
        });
        //var textPlain = mark.value();// markdown 语法的文件
        //var textMarkdown = mark.markdown(textPlain); html 语法
        /*$("#doc-ta-1").addClass("markdown-body");// 样式*/

    </script>
@endsection
