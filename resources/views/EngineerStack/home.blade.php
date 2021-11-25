<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>ホーム</title>
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
    @if($memos->isEmpty())
        <section class="content">
            <p>まだメモは投稿されていません。</p>
        </section>
    @else 
        <section class="content">
            <div class="title has-text-centered m-5">
                <p class="is-size-4 is-text-weight-bold">メモ一覧</p>
            </div>
            <div class="columns">
                <div class="column is-4">
                    <div class="categories p-5">
                        <nav class="panel">
                            <p class="panel-heading">
                                カテゴリ
                            </p>
                            <div class="control has-icons-left has-icons-right m-1">
                                <form action="{{ route('memos.search.category') }}" method="GET">
                                    @csrf 
                                    <input class="input is-success" type="text" name="search_word" placeholder="カテゴリでメモを検索" maxlength="100" required>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <div class="select">
                                        <select name="sort">
                                            <option value="ascend">最新のメモを検索</option>
                                            <option value="descend">古い順に検索</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <p class="panel-tabs">
                                <span>最新15カテゴリ</span>
                            </p>
                            <div class="category">
                                @foreach($categories as $category)
                                    <form action="{{ route('memos.search.category') }}">
                                        @csrf
                                        <input type="hidden" name="search_word" value="{{ $category }}" maxlength="100">
                                        <button style="background: none; border: 0px; white-space: normal;"><span class="tag"><i class="fas fa-tape"></i>{{ Str::limit($category, 40) }}</span><br></button>
                                    </form>
                                @endforeach
                            </div>
                            <form action="{{ route('memos.all_categories') }}">
                                @csrf
                                <button type="submit" class="button is-link m-3">カテゴリ一覧へ</button>
                            </form>
                        </nav>
                    </div>
                </div>
                <div class="memos columns is-multiline">
                    @foreach($memos as $memo)
                        <div class="memo column is-5 box m-3" style="min-width: 300px; max-height: 300px;">
                            <div class="category">
                                @foreach($memo->categories->pluck('name') as $category)
                                    <span class="tag"><i class="fas fa-tape"></i>{{ Str::limit($category, 15) }}</span>
                                @endforeach
                            </div><br>
                            <div id="memo_{{ $memo->id }}" style="overflow-wrap: break-word;">
                            </div><br>
                            <div class="memo-data mb-3">
                                <form action="{{ route('memos.show') }}" method="POST">
                                    @csrf 
                                    <input type="hidden" name="memo_id" value="{{ $memo->id }}">
                                    <input type="hidden" name="memo_data" value="{{ $memo->memo_data }}">
                                    <input type="submit" value="メモ詳細へ" class="button is-link">
                                </form>
                            </div>
                            <div class="post-time">
                                <p>{{ $memo->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <nav class="pagination is-centered" role="navigation" aria-label="pagination">
                        <ul class="pagination-list">
                            @if($memos->currentPage() == 1)
                                @if($memos->currentPage() == $memos->lastPage())
                                    <li><a class="pagination-link is-current" aria-current="page">{{ $memos->currentPage() }}</a></li>
                                @else 
                                    <li><a class="pagination-link is-current" aria-current="page">{{ $memos->currentPage() }}</a></li>
                                    <li><a class="pagination-next" href="{{ $memos->nextPageUrl() }}">次のページ</a></li>
                                @endif
                            @elseif($memos->currentPage() == $memos->lastPage())
                                <li><a class="pagination-previous" href="{{ $memos->previousPageUrl() }}">前のページ</a></li>
                                <li><a class="pagination-link is-current" aria-current="page">{{ $memos->currentPage() }}</a></li>
                            @else 
                                <li><a class="pagination-previous" href="{{ $memos->previousPageUrl() }}">前のページ</a></li>
                                <li><a class="pagination-link is-current" aria-current="page">{{ $memos->currentPage() }}</a></li>
                                <li><a class="pagination-next" href="{{ $memos->nextPageUrl() }}">次のページ</a></li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        </section>
    @endif
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
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/navbar.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"
                integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
                crossorigin="anonymous"></script>
    <script>
        $(function () {
            let memos = @json($memos);
            const memosLength = memos['data'].length;
            for(let index = 0; index < memosLength; index++) {
                const id = `#memo_${memos['data'][index]['id']}`;
                let text = sanitizeDecode(memos['data'][index]['memo_text']);
                text = truncate(text);
                $(id).text(text);
            }
        });

        function truncate(str){
            return str.length <= 100 ? str: (str.substr(0, 100)+"...");
        }

        function sanitizeDecode(text) {
            return text.replace(/&lt;/g, '<')
            .replace(/&gt;/g, '>')
            .replace(/&quot;/g, '"')
            .replace(/&#39;/g, '\'')
            .replace(/&amp;/g, '&');
        }
    </script>
</body>
</html>