@extends('app.layouts.base')

@section('head')
@endsection

@section('nav')
@endsection

@section('content')
        <article class="post clearfix" itemscope itemtype="http://schema.org/Article">
            <header class="post-header">
                <h1 class="entry-title">{{ $post->title }}</h1>
                <div class="post-date">
                    <time class="fa fa-calendar date" datetime="{{$post->created_at->format('c')}}"
                          itemprop="datePublished" pubdate=""> {{$post->created_at->format('d F , Y')}}
                    </time>
                    <span class="views fa fa-eye" itemprop="views"> {{ $post->views}} views</span>
                </div>
                <div class="clear"></div>
            </header>
            <div class="post-content markdown-body" itemprop="articleBody">
                @if(!empty($post->thumb))
                <div class="thumb">
                    <img src="{{ $post->thumb }}?imageMogr2/thumbnail/!75p"/>
                </div>
                @endif
                {!! $post->content !!}
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
        <div id="comment">
            <myp-comment post="{{ $post->id }}"></myp-comment>
        </div>
@endsection

@section('pager')
@endsection

@section('footer')
@endsection

@section('scripts')
    <script src="{{ mix('js/app.js') }}"></script>
@endsection