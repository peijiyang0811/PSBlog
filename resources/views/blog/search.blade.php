@extends('layout.home')
@section('title', '搜索')
@section('contents')
    {{--article--}}
    <div class="am-u-md-12 am-u-sm-12">
        @foreach($data as $value)
            <article class="am-g blog-entry-article">
                <div class="am-u-lg-6 am-u-md-12 am-u-sm-12 blog-entry-img">
                    <img src="{{asset($value -> image)}}" alt="" class="am-u-sm-12">
                </div>
                <div class="am-u-lg-6 am-u-md-12 am-u-sm-12 blog-entry-text">
                    <span><a href="" class="blog-color">{{$value -> cate_title}}</a></span>
                    <span> @author {{$value -> user_name}}</span>
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
        {{$data -> links()}}
    </div>
@endsection
