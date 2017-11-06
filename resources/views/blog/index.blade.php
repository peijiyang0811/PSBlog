@extends('layout.home')
@section('title', '首页')
@section('contents')
    <!-- banner start -->
    <div class="am-g am-g-fixed blog-fixed am-u-sm-centered blog-article-margin">
        <div data-am-widget="slider" class="am-slider am-slider-b1" data-am-slider='{&quot;controlNav&quot;:false}' >
            {{--<ul class="am-slides">
                @foreach($pictures as $picture)
                    <li>
                        <img src="{{asset($picture -> image)}}">
                        <div class="blog-slider-desc am-slider-desc ">
                            <div class="blog-text-center blog-slider-con">
                                <span><a href="{{url('/article/'.$picture->article_uuid)}}" class="blog-color">{{$picture -> cate_title}}&nbsp;</a></span>
                                <h1 class="blog-h-margin"><a href="{{url('/article/'.$picture->article_uuid)}}">{{$picture -> title}}</a></h1>
                                <p>{{$picture -> subtitle}}</p>
                                <span class="blog-bor">{{date('Y-m-d H:i', $picture -> create_time)}}</span>
                                <br><br><br><br><br><br><br>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>--}}
        </div>
    </div>
    <!-- banner end -->
    {{--article--}}
    <div class="am-u-md-8 am-u-sm-12">
        @foreach($article as $value)
            <article class="am-g blog-entry-article">
                <div class="am-u-lg-6 am-u-md-12 am-u-sm-12 blog-entry-img">
                    <img src="{{asset($value -> image)}}" alt="" class="am-u-sm-12">
                </div>
                <div class="am-u-lg-6 am-u-md-12 am-u-sm-12 blog-entry-text">
                    <span><a href="" class="blog-color">{{$value -> cate_title}}&nbsp;</a></span>
                    <span>@author: &nbsp;{{$value -> user_name}}</span>
                    <span>{{formatDate($value -> update_time)}}</span>
                    <h1><a href="{{url('/article/'.$value->article_uuid)}}">{{$value -> title}}</a></h1>
                    <p>{{$value -> subtitle}}</p>
                    <div class="am-comment-actions">
                        <a href=""><i class="am-icon-thumbs-up"></i> {{$value -> vote_count}}</a>
                        <a href=""><i class="am-icon-eye"></i> {{$value -> visit_count}}</a>
                        <a href=""><i class="am-icon-heart"></i> {{$value -> collect_count}}</a>
                    </div>
                </div>
            </article>
        @endforeach
        {{$article -> links()}}
    </div>
    {{--tag links--}}
    <div class="am-u-md-4 am-u-sm-12 blog-sidebar">
        <div class="blog-clear-margin blog-sidebar-widget blog-bor am-g ">
            <h2 class="blog-title"><span>TAG cloud</span></h2>
            <div class="am-u-sm-12 blog-clear-padding">
                <a href="" class="blog-tag">amaze</a>
                <a href="" class="blog-tag">妹纸 UI</a>
                <a href="" class="blog-tag">HTML5</a>
                <a href="" class="blog-tag">这是标签</a>
                <a href="" class="blog-tag">Impossible</a>
                <a href="" class="blog-tag">开源前端框架</a>
            </div>
        </div>
        <div class="blog-sidebar-widget blog-bor">
            <h2 class="blog-text-center blog-title"><span>About ME</span></h2>
            <img src="assets/i/f14.jpg" alt="about me" class="blog-entry-img" >
            <p>裴纪阳</p>
            <p></p>
            <p>我不想成为一个庸俗的人。十年百年后，当我们死去，质疑我们的人同样死去，后人看到的是裹足不前、原地打转的你，还是一直奔跑、走到远方的我？</p>
        </div>
        <div class="blog-sidebar-widget blog-bor">
            <h2 class="blog-text-center blog-title"><span>Contact ME</span></h2>
            <p>
                <a href=""><span class="am-icon-qq am-icon-fw am-primary blog-icon"></span></a>
                <a href=""><span class="am-icon-github am-icon-fw blog-icon"></span></a>
                <a href=""><span class="am-icon-weibo am-icon-fw blog-icon"></span></a>
                <a href=""><span class="am-icon-reddit am-icon-fw blog-icon"></span></a>
                <a href=""><span class="am-icon-weixin am-icon-fw blog-icon"></span></a>
            </p>
        </div>
        <div class="blog-sidebar-widget blog-bor">
            <h2 class="blog-title"><span>友情链接</span></h2>
            <ul class="am-list">
                <li><a href="#">每个人都有一个死角， 自己走不出来，别人也闯不进去。</a></li>
            </ul>
        </div>
    </div>
@endsection