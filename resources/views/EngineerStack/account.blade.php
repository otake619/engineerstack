<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>アカウント設定</title>
</head>
<body>
    <section class="header">
        <nav class="navbar" role="navigation" aria-label="main navigation">
            <div class="navbar-brand">
                <a class="navbar-item is-size-3 has-text-weight-semibold has-text-primary" href="{{ route('dashboard') }}">
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
                        <div class="field ml-5">
                            <div class="control has-icons-left has-icons-right">
                                <form action="{{ route('memos.search') }}" method="GET" class="is-flex">
                                    @csrf 
                                    <div class="input-keyword">
                                        <input class="input is-success is-6" type="text" name="search_word" placeholder="キーワードで検索" maxlength="100" required>
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-search"></i>
                                        </span>
                                    </div>
                                    <div class="select">
                                        <select name="sort">
                                            <option value="ascend">最新のメモを検索</option>
                                            <option value="descend">古い順に検索</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="navbar-item">
                        <div class="buttons">
                            <a class="button is-primary" href="{{ route('memos.get.input') }}">
                                <strong>記録</strong>
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <input type="submit" class="button is-light" value="ログアウト">
                            </form>
                        </div>
                    </div>
                    <div class="navbar-item">
                        <form action="{{ route('user.show') }}" method="GET">
                            @csrf
                            <button style="background: transparent; border:transparent"><i class="fas fa-user has-text-info is-size-4"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </section>
    <section class="message">
        @isset($message)
            <div class="notification is-success has-text-centered">
                <p>{{ $message }}</p>
            </div>
        @endisset
        @if (session('message'))
            <div class="notification is-success has-text-centered">
                {{ session('message') }}
            </div>
        @endif
        @if (session('alert'))
            <div class="notification is-warning has-text-centered">
                {{ session('alert') }}
            </div>
        @endif
    </section>
    <section class="content">
        <div class="columns">
            <div class="column"></div>
            <div class="column">
                <div class="title has-text-centered m-5">
                    <p class="is-size-4 is-text-weight-bold">アカウント設定</p>
                </div>
                <div class="account has-text-centered">
                    <label for="name">アカウント名</label>
                    <div class="control">
                        <form action="{{ route('user.update.name') }}" method="POST">
                            @csrf
                            <input type="text" id="name" name="name" class="input is-hovered" value="{{ $user->name }}">
                            <input type="submit" class="button is-primary mt-4" value="変更">
                        </form>
                    </div>
                    <p class="mt-3">Email: {{ $user->email }}</p>
                    <a href="" class="has-text-info">Emailを変更する場合はこちら</a>
                    <p class="mt-3">PassWord: *********</p>
                    <a href="" class="has-text-info">PassWordを変更する場合はこちら</a><br><br><br>
                    <a href="{{ route('dashboard') }}" class="has-text-info">ホームへ戻る</a>
                </div>
            </div>
            <div class="column"></div>
        </div>
    </section>
    <section class="footer">
        <div class="columns">
            <div class="column">
                <a class="navbar-item is-size-5 has-text-weight-semibold has-text-primary" href="{{ route('dashboard') }}">
                    EngineerStack
                </a>
                <span class="m-3">&copy;otake619 2021</span>
            </div>
            <div class="column m-3">
                <p class="mb-3">EngineerStack</p>
                <a class="has-text-primary" href="">利用規約</a><br>
                <a class="has-text-primary" href="">リリース</a><br>
                <a class="has-text-primary" href="">プライバシーポリシー</a><br>
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
        $(function () {

        });
    </script>
</body>
</html>