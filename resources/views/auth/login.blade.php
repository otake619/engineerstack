<!DOCTYPE html>
<html lang="ja" prefix="og: http://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta property="og:title" content="ログイン">
    <meta property="og:type" content="website">
    <meta property="og:description" content="エンジニア向けのシンプルなメモアプリです。">
    <meta property="og:url" content="https://engineerstack-app-laravel8.herokuapp.com/login">
    <meta property="og:site_name" content="EngineerStack">
    <meta property="og:image" content="https://engineerstack-app-laravel8.herokuapp.com/images/site_icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>ログイン EngineerStack</title>
</head>
<body>
    <section class="header">
        <nav class="navbar" role="navigation" aria-label="main navigation">
            <div class="navbar-brand">
                <a class="navbar-item is-size-3 has-text-weight-semibold has-text-primary" href="{{ route('login') }}">
                    EngineerStack
                </a>
                <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>
            <div id="navbarBasicExample" class="navbar-menu">
                <div class="navbar-end">
                    <div class="navbar-item">
                        <div class="buttons">
                            <a class="button is-primary" href="{{ route('register') }}">
                                <strong>アカウント作成</strong>
                            </a>
                            <a class="button is-light" href="{{ route('login') }}">
                                ログイン
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </section>
    <section class="content has-background-primary m-5 p-5">
        <div class="form">
            <div class="m-5">
                <div class="columns">
                    <div class="column">
                    </div>
                    <div class="column is-three-fifths">
                        @if ($errors->any())
                            <div class="notification is-danger has-text-centered">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </ul>
                            </div>
	                    @endif
                        <form action="{{ route('login') }}" method="POST" >
                            @csrf
                            <h4 class="has-text-white">ご登録情報を入力してください。</h4>
                            <label for="email" class="has-text-white"><span class="has-text-danger">*必須 </span>メールアドレス</label>
                            <input class="input" id="email" type="email" name="email" placeholder="engineer@stack.com" required autofocus>
                            <label for="password" class="has-text-white"><span class="has-text-danger">*必須 </span>パスワード</label>
                            <input class="input" id="password" name="password" type="password" placeholder="********" required>
                            <div class="block mt-4">
                                <label for="remember_me" class="inline-flex items-center">
                                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                                    <span class="ml-2 text-sm has-text-white">{{ __('ログイン情報を記録') }}</span>
                                </label>
                            </div>
                            <input type="submit" class="button is-primary is-light mt-1" value="ログイン">
                        </form>
                        <br>
                        <a class="has-text-white" href="{{ route('password.request') }}">パスワードを忘れた場合</a>
                    </div>
                    <div class="column">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="footer">
        <div class="columns">
            <div class="column">
                <a class="navbar-item is-size-5 has-text-weight-semibold has-text-primary" href="{{ route('login') }}">
                    EngineerStack
                </a>
                <span class="m-3">&copy;otake619 2021</span>
            </div>
            <div class="column m-3">
                <p class="mb-3">EngineerStack</p>
                <a class="has-text-primary" href="{{ route('guidelines') }}">利用規約</a><br>
                <a class="has-text-primary" href="{{ route('privacy_policy') }}">プライバシーポリシー</a><br>
                <a class="has-text-primary" href="{{ route('contact.index') }}">お問い合わせ</a>
            </div>
        </div>
    </section>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/navbar.js') }}"></script>
</body>
</html>
