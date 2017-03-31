<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta charset="UTF-8">
    <title>{{ bloginfo('site_name') }} - 管理后台</title>
    <link rel="stylesheet" href="{{ mix('backend/css/app.css') }}">
</head>
<body>
<div id="app">
    <div class="pit-loadding-box">
        <img src="{{ asset('backend/images/fly.gif') }}" class="pit-loading-img" alt="">
    </div>
</div>
<script>window.Laravel = {'csrfToken' : '{{csrf_token()}}','apiUrl':'{{ route('admin') }}'};</script>
<script src="{{ mix('backend/js/app.js') }}"></script>
</body>
</html>