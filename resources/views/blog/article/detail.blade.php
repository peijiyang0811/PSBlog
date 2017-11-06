@extends('layout.home')
@section('title', $article->title)
@section('contents')
    <div class="am-g am-g-fixed blog-fixed blog-content">
        <div class="am-u-sm-12">
            <article class="am-article blog-article-p">
                <div class="am-article-hd">
                    <h1 class="am-article-title blog-text-center">{{$article -> title}}</h1>
                    <p class="am-article-meta blog-text-center">
                        <span><a href="#" class="blog-color">{{$article -> cate_title}} &nbsp;</a></span>-
                        <span><a href="#">@ {{$article -> user_name}} &nbsp;</a></span>-
                        <span>创建于:{{date('Y-m-d H:i', $article -> create_time)}}</span> &nbsp;&nbsp;&nbsp;
                        <span>最后更新于:{{date('Y-m-d H:i', $article -> update_time)}}</span>
                    </p>
                </div>
                <div class="am-article-bd am-u-sm-centered blog-text-center">
                    <img  src="{{asset($article -> image)}}" alt="" class="am-u-sm-centered blog-text-center blog-entry-img blog-article-margin">
                </div>
            </article>

            <div class="am-g blog-article-widget blog-article-margin">
                {{--am-u-sm-centered blog-text-center--}}
                <div class="am-u-lg-offset-1 am-u-sm-offset-1 am-u-md-offset-1 am-u-lg-10 am-u-md-10 am-u-sm-10 markdown-body">
                    <div>
                        {!! $article -> contents !!}
                    </div>
                </div>

            </div>
            <div class="am-g blog-article-widget am-u-sm-centered blog-text-center blog-article-margin ">
                <span class="am-icon-tags"> &nbsp;</span><a href="#">标签</a> , <a href="#">TAG</a> , <a href="#">啦啦</a>
                <hr>
                <a href=""><span class="am-icon-qq am-icon-fw am-primary blog-icon"></span></a>
                <a href=""><span class="am-icon-wechat am-icon-fw blog-icon"></span></a>
                <a href=""><span class="am-icon-weibo am-icon-fw blog-icon"></span></a>
            </div>
            <hr>
            <ul class="am-pagination blog-article-margin">
                {{--上一篇--}}
                @if ($prev)
                    <li class="am-pagination-prev"><a href="{{url('/article/'.$prev->article_uuid)}}" class="">« {{$prev -> title}}</a></li>
                @endif
                {{--下一篇--}}
                @if ($next)
                    <li class="am-pagination-next"><a href="{{url('/article/'.$next->article_uuid)}}">{{$next -> title}} »</a></li>
                @endif
            </ul>

            <hr>
            {{--留言--}}
            <div class="am-g blog-author blog-article-margin">
                <ul class="am-comments-list am-comments-list-flip">
                    @foreach($comments as $comment)
                        @if ($comment -> parent_id)
                            <li class="am-comment  am-comment-flip am-comment-secondary">
                                <a href="#">
                                    <img src="{{asset($comment -> user_avatar)}}" alt="" class="am-comment-avatar" width="48" height="48"></a>
                                <div class="am-comment-main">
                                    <header class="am-comment-hd">
                                        <div class="am-comment-meta">
                                            <a href="" class="am-comment-author">{{$comment -> user_name}}</a>&nbsp;&nbsp;评论于
                                            <time datetime="" title="">{{formatDate($comment -> create_time)}}</time>
                                        </div>
                                    </header>
                                    <div class="am-comment-bd">
                                        <p>
                                            <a href="">@ {{$comment -> at_user_name}}</a> &nbsp;
                                            {{$comment -> contents}}
                                        </p>
                                    </div>
                                    <footer class="am-comment-footer">
                                        <div class="am-comment-actions">
                                            &nbsp;&nbsp;
                                            <span style="cursor: pointer" user_name="{{$comment -> user_name}}" user_uuid="{{$comment -> at_user_uuid}}" commet-id="{{$comment -> id}}" class="recomment"><i class="am-icon-comments"></i>&nbsp;&nbsp;回复</span>
                                        </div>
                                    </footer>
                                </div>
                            </li>
                        @else
                            <li class="am-comment">
                                <a href="">
                                    <img src="{{asset($comment -> user_avatar)}}" alt="" class="am-comment-avatar" width="48" height="48"></a>
                                <div class="am-comment-main">
                                    <header class="am-comment-hd">
                                        <div class="am-comment-meta">
                                            <a href="" class="am-comment-author">{{$comment -> user_name}}</a>&nbsp;&nbsp;评论于
                                            <time datetime="" title="">{{formatDate($comment -> create_time)}}</time></div>
                                    </header>
                                    <div class="am-comment-bd">
                                        <p>
                                            {{$comment -> contents}}
                                        </p>
                                    </div>
                                    <footer class="am-comment-footer">
                                        <div class="am-comment-actions">
                                            &nbsp;&nbsp;
                                            <span style="cursor: pointer" user_name="{{$comment -> user_name}}" user_uuid="{{$comment -> user_uuid}}" commet-id="{{$comment -> id}}" class="recomment"><i class="am-icon-comments"></i>&nbsp;&nbsp;回复</span>
                                        </div>
                                    </footer>
                                </div>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            {{--回复评论 am-popup am-modal-alert--}}
            <div class="am-modal am-modal-no-btn" tabindex="-1" id="replay">
                <div class="am-modal-dialog">
                    <div class="am-popup-hd">
                        <h4 class="am-popup-title">回复 - 女爵</h4>
                        <span data-am-modal-close="" class="am-close">×</span></div>
                    <div class="am-popup-bd">
                        <form class="am-form am-g">
                            <input type="hidden" name="parent_id">
                            <input type="hidden" name="at_user_uuid">
                            <input type="hidden" name="article_uuid">
                            <h3 class="blog-comment">评论</h3>
                            <fieldset>

                                <div class="am-form-group">
                                    <textarea name="contents" class="" rows="6" style="resize: none" placeholder="一字千金"></textarea>
                                </div>

                                <p><button type="button" class="am-btn am-btn-success">发表评论</button></p>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>

            <hr>
            {{--提交评论--}}
            <form class="am-form am-g">
                <h3 class="blog-comment">评论</h3>
                <fieldset>

                    <div class="am-form-group">
                        <textarea name="contentsa" class="" rows="10" style="resize: none" placeholder="一字千金"></textarea>
                    </div>
                    @if (session('home_user_id'))
                        <p><button type="button" class="am-btn am-btn-secondary">发表评论</button></p>
                    @else
                        <p><a href="{{url('/login')}}" class="am-btn am-btn-default">请先登陆</a></p>
                    @endif
                </fieldset>
            </form>
            <hr>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $('.am-btn-secondary').click(function() {
            var uuid = '{{$article -> article_uuid}}';
            var contents = $('textarea[name=contentsa]').val();
            $.ajax({
                url:"/center/comments/addComments",
                dataType:"JSON",
                data:{article_uuid:uuid, contents: contents},
                type:"post",
                success:function(json) {
                    console.log(json);
                    if (json.code != 200) {
                        alertInfo($('#alert-modal'), '错误', json.message);
                        return;
                    }
                    // 添加成功
                    var li_html = '<li class="am-comment"><a href=""><img src="'+json.data.user_avatar+'" alt="" class="am-comment-avatar" width="48" height="48"></a><div class="am-comment-main"><header class="am-comment-hd"><div class="am-comment-meta"><a href="" class="am-comment-author">'+json.data.user_name+'</a>&nbsp;&nbsp;评论于 <time datetime="" title=""> '+json.data.create_time+'</time></div></header><div class="am-comment-bd"><p>'+json.data.contents+'</p></div><footer class="am-comment-footer"><div class="am-comment-actions">&nbsp;&nbsp;<span style="cursor: pointer" user_name="'+json.data.user_name+'" user_uuid="'+json.data.user_uuid+'" commet-id="'+json.data.id+'" class="recomment"><i class="am-icon-comments"></i>&nbsp;&nbsp;回复</span></div></footer></div></li>';
                    $('textarea[name=contentsa]').val(' ');
                    $('.am-comments-list-flip').prepend(li_html);
                    alertInfo($('#alert-modal'), '成功', '添加评论成功');
                }
            });
        });
        $('.recomment').each(function(){
            var this_replay = $(this);
            this_replay.click(function(){
                var parent_id = this_replay.attr('commet-id');
                var at_user_id = this_replay.attr('user_uuid');
                var at_user_name = this_replay.attr('user_name');
                var article_uuid = '{{$article -> article_uuid}}';
                $('.am-popup-title').html(' 回复 - ' + at_user_name);
                $('input[name=parent_id]').val(parent_id);
                $('input[name=at_user_uuid]').val(at_user_id);
                $('input[name=article_uuid]').val(article_uuid);
                $('#replay').modal();
                $('.am-btn-success').click(function() {
                    var uuid = '{{$article -> article_uuid}}';
                    var contents = $('textarea[name=contents]').val();
                    $.ajax({
                        url:"/center/comments/addComments",
                        dataType:"JSON",
                        data:{
                            contents: contents,
                            parent_id:$('input[name=parent_id]').val(),
                            article_uuid:$('input[name=article_uuid]').val(),
                            at_user_uuid:$('input[name=at_user_uuid]').val()
                        },
                        type:"post",
                        success:function(json) {
                            console.log(json);
                            if (json.code != 200) {
                                alertInfo($('#alert-modal'), '错误', json.message);
                                return;
                            }
                            // alertInfo($('#alert-modal'), '成功', '添加评论成功');
                            window.location.reload()
                        }
                    });
                });
            });
        });
    </script>
@endsection