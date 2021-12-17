<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>アカウント作成 EngineerStack</title>
</head>
<body>
    <section class="header">
        <nav class="navbar" role="navigation" aria-label="main navigation">
            <div class="navbar-brand">
                <a class="navbar-item is-size-3 has-text-weight-semibold has-text-primary" href="{{ route('admin.login') }}">
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
                            <a class="button is-primary" href="{{ route('admin.register') }}">
                                <strong>アカウント作成</strong>
                            </a>
                            <a class="button is-light" href="{{ route('admin.login') }}">
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
            <div class="columns">
                <div class="column">
                </div>
                <div class="column is-three-fifths">
                    <div class="introduction">
                        <h4 class="has-text-white is-size-4 ml-5">EngineerStack(エンジニアスタック)とは？</h4>
                        <p class="has-text-white is-size-5 ml-5">
                            EngineerStackは、エンジニア向けのメモです。
                            つまづいた点をメモとして蓄積することで、
                            再び同じエラーに遭遇した際にメモを参照するだけで
                            解決します。
                        </p>
                        <h4 class="has-text-white is-size-4 mt-5 ml-5">このような使い方もできます！</h4>
                        <p class="has-text-white is-size-5 mt-5 ml-5">
                            EngineerStackには、蓄積したメモをCSV形式で
                            出力する機能を備えています。
                            転職の際に表計算ソフトにまとめて、
                            採用担当者に提出することで、ご自身の
                            転職への熱意や知識量、学習量を伝えることができます。
                        </p>
                    </div>
                    <hr>
                </div>
                <div class="column">
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
                            <form action="{{ route('admin.register') }}" method="POST">
                                @csrf
                                <label for="name" class="has-text-white"><span class="has-text-danger">*必須 40文字以内 </span>アカウント名</label>
                                <input class="input" id="name" type="text" name="name" placeholder="stack.json" required autofocus>
                                <label for="email" class="has-text-white"><span class="has-text-danger">*必須 </span>メールアドレス</label>
                                <input class="input" id="email" name="email" type="email" placeholder="engineer@stack.com" required>
                                <label for="password" class="has-text-white"><span class="has-text-danger">*必須 8-50文字半角英数字 </span>パスワード</label>
                                <input class="input" id="password" name="password" type="password" placeholder="********" required>
                                <label for="password" class="has-text-white"><span class="has-text-danger">*必須 </span>パスワード(確認用)</label>
                                <input class="input" id="password" name="password_confirmation" type="password" placeholder="********" required>
                                <label class="checkbox has-text-white mt-3">
                                    <input type="checkbox">
                                    <a href="#">利用規約</a>に同意する
                                </label><br>
                                <input type="submit" class="button is-primary is-light mt-5" value="アカウント作成">
                            </form>
                            <br>
                            <a class="has-text-white" href="{{ route('admin.login') }}">登録済みの場合はこちら</a>
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
                <a class="navbar-item is-size-5 has-text-weight-semibold has-text-primary" href="{{ route('admin.login') }}">
                    EngineerStack
                </a>
                <span class="m-3">&copy;otake619 2021</span>
            </div>
            <div class="column m-3">
                <p class="mb-3">EngineerStack</p>
                <a class="has-text-primary" href="">利用規約</a><br>
                <a class="has-text-primary" href="">リリース</a><br>
                <a class="has-text-primary" href="">プライバシーポリシー</a><br>
                <a class="has-text-primary" href="">お問い合わせ</a>
            </div>
        </div>
    </section>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/navbar.js') }}"></script>
</body>
</html>