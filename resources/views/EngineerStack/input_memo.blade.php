<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>メモ記録 EngineerStack</title>
</head>
<body>
    <section class="header">
        <nav class="navbar" role="navigation" aria-label="main navigation">
            <div class="navbar-brand">
                <a class="navbar-item is-size-3 has-text-weight-semibold has-text-primary" href="">
                    EngineerStack
                </a>
                <div class="field mt-4 ml-5">
                    <div class="control has-icons-left has-icons-right">
                        <input class="input is-success" type="text" placeholder="キーワードを入力">
                        <span class="icon is-small is-left">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </div>
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
                            <a class="button is-primary" href="{{ route('memos.get.form') }}">
                                <i class="fas fa-pen"></i><strong>記録</strong>
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
        <div class="input-memo p-5">
            <div class="columns">
                <div class="column"></div>
                <div class="column is-three-fifths">
                    <div class="title mt-5">
                        <h4 class="is-size-4">メモをとる</h4>
                    </div>
                    <form action="{{ route('memos.store') }}" method="POST">
                        @csrf
                        @if ($errors->any())
                            <div class="has-text-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
	                    @endif
                        <div class="field">
                            <label for="comment">カテゴリ<br><span class="has-text-danger">*必須 最大5個 1カテゴリ30文字まで<br>半角カンマ「,」で区切って入力</span></label><br>
                            <span class="has-text-primary" id="count_category">残り5個入力可能</span>
                            <span class="is-primary" id="disp_category"></span>
                            <div class="control has-text-centered">
                                <div class="field">
                                    <div class="control">
                                        <input type="text" name="categories" class="input is-success" id="category" placeholder="カテゴリ1, カテゴリ2, カテゴリ3,...">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label for="comment">タイトル<br><span class="has-text-danger">*必須 最大100文字まで</span></label><br>
                            <span class="has-text-primary" id="count_title">残り100文字入力可能</span>
                            <div class="control has-text-centered">
                                <div class="field">
                                    <div class="control">
                                        <input type="text" name="title" id="title" class="input is-success" placeholder="タイトルを入力" maxlength="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="editor_field">
                            <label for="editor">コンテンツ<br><span>*任意</span></label>
                            <div id="editor" style="border: 1px solid #00d1b2; border-radius: 4px;"></div>
                        </div>
                        <button id="post_memo" class="button is-primary m-2">
                            <p class="is-size-4"><i class="fas fa-save"></i>保存</p>
                        </button>
                    </form>
                </div>
                <div class="column"></div>
            </div>
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
                <a class="has-text-primary" href="">お問い合わせ</a>
            </div>
        </div>
    </section>
    <script src="{{ asset('js/input_memo.js') }}"></script>
    <script src="{{ asset('js/navbar.js') }}"></script>
</body>
</html>