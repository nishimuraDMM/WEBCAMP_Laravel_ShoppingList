<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ログイン機能付きタスク管理サービス 管理画面 @yield('title')</title>
    </head>
    <body>
             
<a href="/admin/user/top">管理画面トップ</a><br>
<a href="/admin/user/list">ユーザ一覧</a><br>

<a href="/admin/logout">ログアウト</a><br>

        @yield('contents')
    </body>
</html>