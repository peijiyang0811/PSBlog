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

                        <div class="am-form-group" id="my-editormd">
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
        var myEditor;

        $(function() {
            myEditor = editormd("my-editormd", {//注意1：这里的就是上面的DIV的id属性值
                width   : "100%",
                height  : 400,
                syncScrolling : "single",
                path    : "/editorMd/lib/",//注意2：你的路径
                saveHTMLToTextarea : false,//注意3：保存md 语法的 html 格式
                //后端要想获得第二个textarea中的值，首先需要打开editor.md的saveHTMLToTextarea : true设置（见下面）；
                // 这个配置，方便post提交表单
                emoji: true,//emoji表情，默认关闭
                searchReplace:true,// 搜索替换
                taskList: true,
                tocm: true, // Using [TOCM]
                tex: true,// 开启科学公式TeX语言支持，默认关闭
                flowChart: true,//开启流程图支持，默认关闭
                sequenceDiagram: true,//开启时序/序列图支持，默认关闭,
                dialogLockScreen : false,//设置弹出层对话框不锁屏，全局通用，默认为true
                dialogShowMask : true,//设置弹出层对话框显示透明遮罩层，全局通用，默认为true
                dialogDraggable : true,//设置弹出层对话框不可拖动，全局通用，默认为true
                dialogMaskOpacity : 0.2, //设置透明遮罩层的透明度，全局通用，默认值为0.1
                dialogMaskBgColor : "#000",//设置透明遮罩层的背景颜色，全局通用，默认为#fff
                codeFold: true,
                // 图片上传配置
                imageUpload : true,
                imageFormats : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
                imageUploadURL : "{{url('center/blog/upload/image')}}",//注意你后端的上传图片服务地址
                /*上传图片成功后可以做一些自己的处理*/
                onload: function () {
                    //console.log('onload', this);
                    //this.fullscreen();
                    //this.unwatch();
                    //this.watch().fullscreen();
                    //this.width("100%");
                    //this.height(480);
                    //this.resize("100%", 640);
                },
                /**设置主题颜色*/
                /*
                * default 3024-day 3024-night ambiance ambiance-mobile base16-dark base16-light
                * blackboard cobalt eclipse elegant erlang-dark lesser-dark mbo mdn-like midnight
                * monokai neat neo night paraiso-dark paraiso-light pastel-on-dark rubyblue solarized
                * the-matrix tomorrow-night-eighties twilight vibrant-ink xq-dark xq-light
                * */
                editorTheme: "mdn-like",// 左侧md语法栏 编辑区域主题
                theme: "default",// 导航栏沿着 default | dark
                previewTheme: "default"// 右侧html语法栏 default | dark
            });
        });
        /*var mark = new SimpleMDE({
            element: document.getElementById("doc-ta-1"),
            autoDownloadFontAwesome: false,
            placeholder: "Type here...",
            autosave: {
                enabled: true,
                unique_id: "doc-ta-1"
            }
        });*/
        //var textPlain = mark.value();// markdown 语法的文件
        //var textMarkdown = mark.markdown(textPlain); html 语法
        /*$("#doc-ta-1").addClass("markdown-body");// 样式*/

    </script>
@endsection
