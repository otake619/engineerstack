<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>EngineerStack お問い合わせ</title>
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
                </div>
            </div>
        </nav>
    </section>
    <section class="content">
        <div class="title has-text-centered">
            <h4 class="is-size-3">お問い合わせ</h4>
        </div>
        <div class="columns">
            <div class="column">
            </div>
            <div class="column is-three-fifths p-5">
                <div class="form">
                    <div class="errors">
                        @if ($errors->any())
                            <div class="notification is-danger has-text-centered">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    <form action="{{ route('contact.confirm')}}" method="POST">
                        @csrf
                        <div class="field">
                            <label for="comment">お名前 <br><span class="has-text-danger">*必須</span></label>
                            <div class="control">
                                <div class="field"> 
                                    <div class="control">
                                        <input type="text" class="input" name="name" value="{{ old('name') }}" placeholder="氏名を入力" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label for="comment">メールアドレス <br><span class="has-text-danger">*必須</span></label>
                            <div class="control">
                                <div class="field"> 
                                    <div class="control">
                                        <input type="text" class="input" name="email" value="{{ old('email') }}" placeholder="メールアドレスを入力" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label for="comment">ご相談種別 <br><span class="has-text-danger">*必須</span></label>
                            <div class="control">
                                <div class="field"> 
                                    <div class="control">
                                        <div class="select">
                                            <select name="category">
                                                <option>選択してください</option>
                                                <option>本アプリへのご要望</option>
                                                <option>本アプリでのお困りごと</option>
                                                <option>その他</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label for="comment">お問い合わせ内容<br><span class="has-text-danger">*必須</span></label><br>
                            <div class="control has-text-centered">
                                <div class="field">
                                    <div class="control">
                                        <textarea name="body" class="textarea" type="text" placeholder="お問い合わせ内容を入力" rows="10" required>{{ old('body') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input class="button is-primary" type="submit" value="入力内容の確認へ">
                    </form>
                </div>
            </div>
            <div class="column">
            </div>
        </div>
    </section>
    <section class="footer">
        <div class="columns">
            <div class="column">
                <a class="navbar-item is-size-5 has-text-weight-semibold has-text-primary" href="">
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
    <script src="https://code.jquery.com/jquery-3.6.0.js"
                integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
                crossorigin="anonymous"></script>
</body>
</html>