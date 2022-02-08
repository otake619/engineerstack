<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>Eメール認証 EngineerStack</title>
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
        <div class="columns is-centered">
            <div class="column is-half">
                <div class="mb-4">
                    <p>アカウント登録ありがとうございます。アプリをご使用になる前に、ただいま送信させていただいたメールのリンクをクリックして認証してください。もしメールが届いていない場合は、もう一度メールを送らせて頂きます。</p>
                </div>
                @if (session('status') == 'verification-link-sent')
                    <div class="mb-4">
                        <p>新しい認証メールをアカウント登録の際にご入力頂いたメールアドレスあてにお送りいたしました。</p>
                    </div>
                @endif
                <div class="mt-4">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <div>
                            <button>
                                認証メールを再送信する
                            </button>
                        </div>
                    </form>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">
                            ログアウト
                        </button>
                    </form>
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
                <a class="has-text-primary" href="">リリース</a><br>
                <a class="has-text-primary" href="">プライバシーポリシー</a><br>
                <a class="has-text-primary" href="{{ route('contact.index') }}">お問い合わせ</a>
            </div>
        </div>
    </section>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/navbar.js') }}"></script>
</body>
</html>