<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>管理者パスワードリセット EngineerStack</title>
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
                            <a class="button is-light" href="{{ route('admin.login') }}">
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
                @if ($errors->any())
                    <div class="notification is-danger has-text-centered">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('admin.password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                    <div>
                        <label for="email">Eメール</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus />
                    </div>
                    <div class="mt-4">
                        <label for="password">新しいパスワード</label>
                        <input id="password" class="mt-1" type="password" name="password" required/>
                    </div>
                    <div class="mt-4">
                        <label for="password_confirmation">確認用パスワード</label>
                        <input id="password_confirmation" class="mt-1"
                                            type="password"
                                            name="password_confirmation" required/>
                    </div>
                    <div class="mt-4">
                        <button>
                            パスワードをリセット
                        </button>
                    </div>
                </form>
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


