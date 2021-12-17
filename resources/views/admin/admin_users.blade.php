<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>EngineerStack ユーザー一覧</title>
</head>
<body>
    <section class="header">
        <nav class="navbar" role="navigation" aria-label="main navigation">
            <div class="navbar-brand">
                <a class="navbar-item is-size-3 has-text-weight-semibold has-text-primary" href="{{ route('admin.dashboard') }}">
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
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <input type="submit" class="button is-light" value="ログアウト">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </section>
    <section class="content">
        <h4 class="title has-text-centered">ユーザー一覧</h4>
        <div class="columns">
            <div class="select-database column is-one-quarter m-3">
                <a href="{{ route('admin.dashboard') }}">
                    <button class="button is-primary is-outlined is-fullwidth"><i class="fas fa-home"></i>ホーム</button>
                </a>
                <a href="{{ route('admin.get.users') }}">
                    <button class="button is-primary is-outlined is-fullwidth mt-3"><i class="fas fa-users"></i>ユーザー一覧</button>
                </a>
                <a href="{{ route('admin.get.categories') }}">
                    <button class="button is-primary is-outlined is-fullwidth mt-3"><i class="fas fa-tape"></i>カテゴリ一覧</button>
                </a>
                <a href="{{ route('admin.get.memos') }}">
                    <button class="button is-primary is-outlined is-fullwidth mt-3"><i class="fas fa-file-code"></i>メモ一覧</button>
                </a>
            </div>
            <div class="server-info column is-three-quarter">
                <div class="user-info column is-three-quarter">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>
                                    id
                                </th>
                                <th>
                                    アカウント名
                                </th>
                                <th>
                                    E-mail
                                </th>
                                <th>
                                    作成日
                                </th>
                                <th>
                                    利用停止
                                </th>
                                <th>
                                    アカウント削除
                                </th>
                            </tr>
                            @if($users->isEmpty())
                                <div class="notification is-warning has-text-centered">
                                    ユーザーの登録がありません。
                                </div>
                            @else 
                                @foreach ($users as $user)
                                    <tr>
                                        <th>
                                            {{ $user->id }}
                                        </th>
                                        <th>
                                            {{ $user->name }}
                                        </th>
                                        <th>
                                            {{ $user->email }}
                                        </th>
                                        <th>
                                            {{ $user->created_at }}
                                        </th>
                                        <th>
                                            <i class="fas fa-minus-circle has-text-warning"></i>
                                        </th>
                                        <th>
                                            <i class="fas fa-trash-alt has-text-danger"></i>
                                        </th>
                                    </tr>
                                 @endforeach
                            @endif 
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/navbar.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"
                integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
                crossorigin="anonymous"></script>
</body>
</html>