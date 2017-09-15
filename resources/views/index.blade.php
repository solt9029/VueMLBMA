<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>MLBMA</title>
    <link rel="shortcut icon" href="/img/logo.png"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/css/app.css"/>
    <link rel="apple-touch-icon" href="/img/home.jpg" />
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="MLBMA">
    <meta name="twitter:description" content="Meiji Library Books Management Application.明治大学図書館（中野キャンパス）の書籍の貸出状況を確認できる書籍管理アプリケーション。">
    <meta name="twitter:image" content="http://mlbma.solt9029.com/img/background.jpg">
    <meta name="twitter:url" content="http://mlbma.solt9029.com">
    <meta name="twitter:site" content="@solt9029">
    <meta name="twitter:creator" content="@solt9029">
</head>
<body>
    <div id="app">
        <router-view></router-view>
    </div>
    <script src="/js/app.js"></script>
</body>
</html>