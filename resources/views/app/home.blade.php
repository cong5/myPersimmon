@extends('app.layouts.base')

@section('head')
@endsection

@section('nav')
@endsection

@section('content')
    @foreach($posts as $post)
    <article class="post clearfix" itemscope itemtype="http://schema.org/Article">
        <header class="post-header">
            <h1 class="entry-title"><a href="{{ route('post',[$post->flag]) }}" rel="bookmark">{{ $post->title or '' }}</a></h1>
            <div class="post-date">
                <time class="fa fa-calendar date" datetime="{{$post->created_at->format('c')}}"
                      itemprop="datePublished" pubdate=""> {{$post->created_at->format('d F , Y')}}
                </time>
                <span class="views fa fa-eye" itemprop="views"> {{ $post->views}} views</span>
            </div>
            <div class="clear"></div>
        </header>
        <div class="post-content post-desc" itemprop="articleBody">
            <p>{!! get_description($post->content) !!}</p>
            <p>[<a href="{{ route('post',[$post->flag]) }}" rel="nofollow">阅读更多 →</a>]</p>
            <div class="clear"></div>
        </div>
        <footer class="post-footer" itemprop="keywords">
            <li>
                @foreach($post->tags as $tindex => $tag)
                    <i class="fa fa-tag"></i> <a href="{{ route('tags',[$tag->tags_flag]) }}" rel="tag">{{ $tag->tags_name }}</a>&nbsp;&nbsp;
                @endforeach
            </li>
            <div class="clear"></div>
        </footer>
    </article>
    @endforeach
    {{ $posts->links('app.partials.pager') }}
@endsection

@section('footer')
@endsection

@section('scripts')
@endsection
