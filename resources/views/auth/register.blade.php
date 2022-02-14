<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="6Z83FzF_pB3AY2FWDQHOU5hvrGqRQLR8TfOruIae2T0" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>アカウント作成 EngineerStack</title>
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
    <section class="message">
        @if (session('message'))
            <div class="notification is-success has-text-centered">
                {{ session('message') }}
            </div>
        @endif
    </section>
    <section class="content has-background-primary m-5 p-5">
        <div class="introduction">
            <div class="columns is-multiline is-align-items-center">
                <div class="column">
                    <div class="introduction">
                        <h4 class="has-text-white is-size-4 m-5">EngineerStackとは？</h4>
                        <p class="has-text-white is-size-5 m-5">
                            EngineerStackは、エンジニア向けのメモです。
                            つまづいた点をメモとして蓄積することで、再び同じエラーに遭遇した際にメモを参照するだけで解決します。
                        </p>
                    </div>
                </div>
                <div class="column">
                    <img src="{{ asset('images/home_screen.png' )}}" alt="ホーム画面のスクリーンショット">
                </div>
            </div>   
            <div class="columns is-multiline is-align-items-center">
                <div class="column">
                    <img src="{{ asset('images/input_screen.png') }}" alt="メモ記録画面のスクリーンショット">
                </div>
                <div class="column">
                    <div class="introduction-second">
                        <p class="has-text-white is-size-5 m-5">
                            メモには、カテゴリーを5つまで付与することが出来ます。カテゴリーを設定することで、検索しやすさが向上します。
                        </p>
                    </div>
                </div>
            </div> 
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
                            <h4 class="has-text-white">登録項目を入力してください。</h4>
                            <form action="{{ route('register') }}" method="POST">
                                @csrf
                                <label for="name" class="has-text-white"><span class="has-text-danger">*必須 255文字以内 </span>アカウント名</label>
                                <input class="input" id="name" type="text" name="name" placeholder="stack.json" required autofocus>
                                <label for="email" class="has-text-white"><span class="has-text-danger">*必須 </span>メールアドレス</label>
                                <input class="input" id="email" name="email" type="email" placeholder="engineer@stack.com" required>
                                <label for="password" class="has-text-white"><span class="has-text-danger">*必須 8-50文字半角英数字 </span>パスワード</label>
                                <input class="input" id="password" name="password" type="password" placeholder="********" required>
                                <label for="password" class="has-text-white"><span class="has-text-danger">*必須 </span>パスワード(確認用)</label>
                                <input class="input" id="password" name="password_confirmation" type="password" placeholder="********" required>
                                <label class="checkbox has-text-white mt-3">
                                    <input type="checkbox" id="read_guidelines">
                                    <a class="has-text-white is-underlined" href="{{ route('guidelines') }}">利用規約</a>に同意する
                                </label><br>
                                <input type="submit" id="submit" class="button is-primary is-light mt-5" value="アカウント作成" disabled>
                            </form>
                            <br>
                            <a class="has-text-white" href="{{ route('login') }}">登録済みの場合はこちら</a>
                        </div>
                        <div class="column">
                        </div>
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
    <script src="https://code.jquery.com/jquery-3.6.0.js"
                integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
                crossorigin="anonymous"></script>
    <script>
        $('input[type="checkbox"]').change(function() {
            let is_checked = $(this).prop('checked');
            if(is_checked) {
                $('#submit').prop('disabled', false);
            } else {
                $('#submit').prop('disabled', true);
            }
        });
    </script>
</body>
</html>