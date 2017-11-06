@extends('layout.user_center')
@section('title', '我的博客')
@section('user_center')
    <div class="am-u-md-12 am-u-sm-12">
        @if (session('success'))
            <div class="am-alert am-alert-success" data-am-alert>
                {{session('success')}}
            </div>
        @endif
        <div class="am-u-sm-3 am-u-sm-offset-9">
            <span><i class="am-icon-edit"></i>&nbsp;&nbsp;<a href="{{url('/center/blog/add')}}">创建博客</a></span>
        </div>
        @foreach($articles as $article)
            <article class="am-g blog-entry-article">
                <div class="am-u-lg-3 am-u-md-12 am-u-sm-12 blog-entry-img">
                    <img class="am-img-thumbnail am-radius" src="{{asset($article -> image)}}" width="140" height="140">
                </div>
                <div class="am-u-lg-8 am-u-md-12 am-u-sm-12 blog-entry-text">
                    <span class="blog-color">{{$article -> cate_title}}</span>
                    <span> @ {{$article -> user_name}}</span>
                    <span>{{date('Y-m-d H:i', $article -> update_time)}}</span>
                    <h1><a href="{{url('/article/'.$article->article_uuid)}}">{{$article -> title}}</a></h1>
                    <p>{{$article -> subtitle}}</p>
                    <div class="am-comment-actions">
                        <a href=""><i class="am-icon-thumbs-up"></i> {{$article -> vote_count}}</a>
                        <a href=""><i class="am-icon-eye"></i> {{$article -> visit_count}}</a>
                        <a href=""><i class="am-icon-heart"></i> {{$article -> collect_count}}</a>
                    </div>
                    <div class="am-g doc-am-g">
                        <div class="am-u-sm-3">
                            <small class="am-sans-serif">
                                审核状态:
                                @if ($article -> status == 1)
                                    <small class="am-badge am-text-xs am-round">&nbsp;</small>
                                @elseif($article -> status == 2)
                                    <span class="am-badge am-badge-danger am-round">&nbsp;</span>
                                @elseif($article -> status == 3)
                                    <span class="am-badge am-badge-warning am-round">&nbsp;</span>
                                @elseif($article -> status == 4)
                                    <span class="am-badge am-badge-success am-round">&nbsp;</span>
                                @endif
                            </small>
                        </div>
                        <div class="am-u-sm-6">
                            <small class=" ">
                                查看状态:
                                @if ($article -> is_open == 1)
                                    <span class="am-badge am-badge-success am-round">&nbsp;</span>
                                @elseif($article -> is_open == 2)
                                    <a class="am-badge am-badge-danger am-round">&nbsp;</a>
                                @endif
                            </small>
                        </div>
                        <div class="am-u-sm-3">
                            <a href="{{url('/center/blog/edit/'.$article->article_uuid)}}" class="am-btn am-btn-secondary am-round am-btn-xs">编辑</a>
                        </div>
                    </div>
                </div>
            </article>
        @endforeach
        {{$articles -> links()}}
    </div>
@endsection
@section('js')
    <script>
        $('.am-alert-success').fadeOut(5000);
    </script>
@endsection