@extends('app.layouts.base')

@section('head')
@endsection

@section('nav')
@endsection

@section('content')
        <article class="post clearfix" itemscope itemtype="http://schema.org/Article">
            <header class="post-header">
                <h1 class="entry-title links-title">我的邻居</h1>
                <div class="clear"></div>
            </header>
            <div class="post-content markdown-body" itemprop="articleBody">
                <div class="links">
                    <ul>
                        @foreach($links as $link)
                        <li><a href="{{ $link['url'] }}" title="{{ $link['name'] }}" target="_blank"><i class="fa fa-anchor" aria-hidden="true"></i>{{ $link['name'] }}</a></li>
                        @endforeach
                    </ul>
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