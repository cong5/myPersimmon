@extends('app.layouts.base')

@section('head')
@endsection

@section('nav')
@endsection

@section('content')
    <article class="post clearfix" itemscope itemtype="http://schema.org/Article">
        <header class="post-header">
            <h1 class="entry-title links-title">~~~~(>_<)~~~~</h1>
            <div class="clear"></div>
        </header>
        <div class="post-content markdown-body" itemprop="articleBody">
            <div class="links">
                <p> 很抱歉，页面找不到了。 {{ $message or '' }} 回 <a href="/">首页</a> </p>
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