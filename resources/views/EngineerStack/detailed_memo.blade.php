<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>メモ詳細 EngineerStack</title>
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
    <section class="message">
        @isset($message)
            <div class="notification is-success has-text-centered">
                <p>{{ $message }}</p>
            </div>
        @endisset
    </section>
    <section class="content has-background-light pt-5 pb-5">
        <div class="columns mt-5">
            <div class="column">
            </div>
            <div class="column is-three-fifths has-background-white p-5">
                <div class="updated_at">
                    <span>{{ $memo->updated_at->format("Y年m月d日 H時i分s秒") }} 作成</span>
                </div>
                <div class="category">
                    <i class="fas fa-bookmark"></i><span id="categories"></span>
                </div>
                <div class="memo mt-5">
                    <div class="memo-container">
                        <p class="is-size-4">{{ $memo->memo }}</p>
                    </div>
                    <div class="settings has-text-right mt-4">
                        <div class="dropdown">
                            <div class="dropdown-trigger">
                                <button id="settings" class="button" aria-haspopup="true" aria-controls="dropdown-menu3">
                                    <span>編集・削除</span>
                                    <span class="icon is-small">
                                        <i class="fas fa-angle-down" aria-hidden="true"></i>
                                    </span>
                                </button>
                            </div>
                            <div class="dropdown-menu" id="dropdown-menu3" role="menu">
                                <div class="dropdown-content">
                                    <a href="{{ $memo->id }}/edit" class="dropdown-item has-text-left">メモを編集する</a>
                                    <hr class="dropdown-divider">
                                    <button id="delete_memo" class="dropdown-item has-text-left has-text-danger" style="background: none; border: 0px; white-space: normal;">
                                        メモを削除する
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column">
                            </div>
                            <div class="column is-three-fifths">
                                <div class="modal">
                                    <div class="modal-background"></div>
                                    <div class="modal-content">
                                        <div class="confirm">
                                            <header class="modal-card-head">
                                                <p class="modal-card-title has-text-danger has-text-left mt-5">このメモを削除しますが、本当によろしいですか？</p>
                                                <button class="delete" aria-label="close"></button>
                                            </header>
                                            <footer class="modal-card-foot">
                                                <form action="{{ $memo->id }}/destroy" method="POST">
                                                    @csrf 
                                                    <input type="hidden" name="memo_id" value="{{ $memo->id }}">
                                                    <button class="button is-danger"><i class="fas fa-trash-alt"></i>削除</button>
                                                </form>
                                                <button id="modal-close" class="button">キャンセル</button>
                                            </footer>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="column">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column has-background-light">
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
    <script src="{{ asset('js/navbar.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"
                integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
                crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@2.19.3/dist/editor.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/list@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/quote@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/code@latest"></script>
    <script>
        $(function () {
            const categories = @json($categories);
            $('#categories').text(categories);

            $(".settings").click(function() {
                $(".dropdown").toggleClass("is-active");
            });

            $("#delete_memo").click(function() {
                $(".modal").toggleClass("is-active");
            });

            $(".delete").click(function() {
                $(".modal").toggleClass("is-active");
            });

            $("#modal-close").click(function() {
                $(".modal").toggleClass("is-active");
            });

        });
    </script>
</body>
</html>