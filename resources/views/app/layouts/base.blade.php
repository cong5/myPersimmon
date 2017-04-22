<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="x-dns-prefetch-control" content="on" />
    <link rel="dns-prefetch" href="//cdn.bootcss.com" />
    <link rel="dns-prefetch" href="//o75u5ooep.qnssl.com" />
    <link rel="dns-prefetch" href="//s.cong5.net" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="HandheldFriendly" content="True"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="format-detection" content="address=no"/>
    <meta name="format-detection" content="email=no" />
    <title>@if(current_is('tags'))标签 “{{ $tags->tags_name or '' }}” 的文章 - @elseif(current_is('post')){{ $post->title or '' }} - @elseif(current_is('category'))分类 “{{ $category->category_name or '' }}” 的文章 - @endif {{ bloginfo('site_name') }}</title>
    <meta name="keywords" content="{{ bloginfo('keywords') }}" />
    <meta name="description" content="{{ bloginfo('description') }}" />
    <link href="//cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}"/>
    <!--[if lt IE 9]>
    <script src="//cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    @yield('head')
    @if(!empty(bloginfo('google_plus')))
        <link rel="author" href="{{ bloginfo('google_plus') }}" />
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div id="app">
    <div id="header">
        <div class="header-content">
            <hgroup>
                <div class="avatar">
                    <a href="{{ url('/') }}"><img src="{{ cdn('avatar/persimmon.jpg') }}" alt=""></a>
                </div>
                <h1><a href="{{ url('/') }}">{{ bloginfo('site_name') }}</a></h1>
                <div class="description">
                    <p>{{ bloginfo('description') }}</p>
                </div>
            </hgroup>
            <div class="clear"></div>
            <nav class="nav-bar">
                <div class="menu-side-menu-container">
                    <ul id="menu-side-menu" class="menu">
                        <li class="menu-item"><a href="{{ url('/') }}">首页</a></li>
                        @include('app.widgets.navigation')
                        @yield('nav')
                    </ul>
                </div>
            </nav>
            <div class="clear"></div>
            <div class="social-icons" id="my-social">
                <a class="fa fa-weibo" href="{{ bloginfo('weibo') }}" target="_blank" rel="nofollow">
                    <span class="hidden">Weibo</span>
                </a>
                <a class="fa fa-github" href="{{ bloginfo('github') }}" target="_blank" rel="nofollow">
                    <span class="hidden">GitHub</span>
                </a>
                <a class="fa fa-google-plus" href="{{ bloginfo('google_plus') }}" target="_blank" rel="nofollow">
                    <span class="hidden">Google+</span>
                </a>
                <a class="fa fa-rss" href="https://cong5.net/feed/" target="_blank">
                    <span class="hidden">feed</span>
                </a>
            </div>
        </div>
    </div>
    <!--article content-->
    <div id="content">
        <div class="content-main">
            @yield('content')
        </div>

        <div id="footer">
            <div class="footer-content">
                <div class="footer-border">
                    <p><a href="http://www.miitbeian.gov.cn/" target="_blank" rel="nofollow">{{ bloginfo('icp') }}</a></p>
                    <p>本站点采用<a href="https://creativecommons.org/licenses/by-nc-sa/4.0/deed.zh" rel="nofollow">知识共享 署名-非商业性使用-相同方式共享 4.0 国际 许可协议</a></p>
                    <p>Coder by <a href="{{ url('/') }}">@Mr柿子</a>，您可以在 GitHub <i class="fa fa-github"></i> 找到<a href="https://github.com/Cong5/myPersimmon">本站源码</a> - CopyRight &copy; 2017</p>
                    @yield('footer')
                </div>
            </div>
        </div>

    </div>

    <div class="analytics hidden">
        <!-- tongji analytics code -->
        {!! bloginfo('analysis') !!}
    </div>
</div>
@yield('scripts')
</body>
</html>