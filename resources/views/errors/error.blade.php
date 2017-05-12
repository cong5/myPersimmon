@extends('app.layouts.base')

@section('head')
@endsection

@section('nav')
@endsection

@section('content')
    <article class="post clearfix" itemscope itemtype="http://schema.org/Article">
        <header class="post-header">
            <h1 class="entry-title links-title">oh! 好像出错了。</h1>
            <div class="clear"></div>
        </header>
        <div class="post-content markdown-body" itemprop="articleBody">
            <div class="links">
                <p> {{ $message }} </p>
            </div>
            <div class="clear"></div>
        </div>
        <footer class="post-footer" itemprop="keywords">
            <div class="clear"></div>
        </footer>
    </article>
@endsection

@section('pager')
@endsection

@section('footer')
@endsection

@section('scripts')
@endsection