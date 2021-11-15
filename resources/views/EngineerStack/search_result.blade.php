<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>検索結果 EngineerStack</title>
</head>
<body>
    <section class="header">
        <nav class="navbar" role="navigation" aria-label="main navigation">
            <div class="navbar-brand">
                <a class="navbar-item is-size-3 has-text-weight-semibold has-text-primary" href="{{ route('dashboard') }}">
                    EngineerStack
                </a>
                <div class="field mt-4 ml-5">
                    <div class="control has-icons-left has-icons-right">
                        <form action="{{ route('memos.search.title') }}" method="GET">
                            @csrf 
                            <input class="input is-success" type="text" name="search_word" placeholder="キーワードを入力">
                            <span class="icon is-small is-left">
                                <i class="fas fa-search"></i>
                            </span>
                        </form>
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
    @if($hit_memos->isEmpty())
        <section class="content">
            <p>{{ Str::limit($search_word, 10) }} はヒットしませんでした。</p>
        </section>
    @else 
        <section class="content">
            <div class="title has-text-centered m-5">
                <p class="is-size-4 is-text-weight-bold">{{ Str::limit($search_word, 20) }} に関するメモ一覧</p>
            </div>
            <div class="columns">
                <div class="column is-2">
                    <div class="category p-5">
                        @foreach($categories as $category)
                            <form action="{{ route('memos.search.category') }}" method="GET">
                                @csrf 
                                <input type="hidden" name="category" value="{{ $category }}">
                                @if ($category == $search_word)
                                    <button style="background: none; border: 0px; white-space: normal;"><span class="tag is-primary"><i class="fas fa-tape"></i>{{ Str::limit($category, 40) }}</span></button>
                                @else 
                                    <button style="background: none; border: 0px; white-space: normal;"><span class="tag"><i class="fas fa-tape"></i>{{ Str::limit($category, 40) }}</span></button>
                                @endif
                            </form>
                        @endforeach
                    </div>
                </div>
                <div class="memos columns is-multiline">
                    @foreach($hit_memos as $memo)
                        <div class="memo column is-5 box m-3" style="min-width: 300px;">
                            <div class="category">
                                @foreach($memo->categories->pluck('name') as $category)
                                    @if ($category == $search_word)
                                        <span class="tag is-primary"><i class="fas fa-tape"></i>{{ Str::limit($category, 15) }}</span>
                                    @else 
                                        <span class="tag"><i class="fas fa-tape"></i>{{ Str::limit($category, 15) }}</span>
                                    @endif
                                @endforeach
                            </div><br>
                            <div class="title">
                                <form action="{{ route('memos.show') }}" method="POST">
                                    @csrf 
                                    <input type="hidden" name="memo_id" value="{{ $memo->id }}">
                                    <input type="hidden" name="memo_data" id="memo_data" value="{{ $memo->memo_data }}">
                                    <input class="is-size-5 has-text-weight-bold has-text-link" value="{{ Str::limit($memo->title, 100) }}" type="submit"
                                    style="background: none; border: 0px; white-space: normal;">
                                </form>
                            </div>
                            <div id="data_{{ $loop->index }}" style="overflow-wrap: break-word">
                            </div><br>
                            <div class="post-time">
                                <p>{{ $memo->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            {{-- <div class="columns">
                <div class="column">
                    <nav class="pagination is-centered" role="navigation" aria-label="pagination">
                        <ul class="pagination-list">
                            {{-- @if($memos->currentPage() == 1)
                                <li><a class="pagination-link is-current" aria-current="page">{{ $memos->currentPage() }}</a></li>
                                <li><a class="pagination-next" href="{{ $memos->nextPageUrl() }}">次のページ</a></li>
                            @elseif($memos->currentPage() == $memos->lastPage())
                                <li><a class="pagination-previous" href="{{ $memos->previousPageUrl() }}">前のページ</a></li>
                                <li><a class="pagination-link is-current" aria-current="page">{{ $memos->currentPage() }}</a></li>
                            @else  --}}
                                {{-- <li><a class="pagination-previous" href="{{ $memos->previousPageUrl() }}">前のページ</a></li>
                                <li><a class="pagination-link is-current" aria-current="page">{{ $memos->currentPage() }}</a></li>
                                <li><a class="pagination-next" href="{{ $memos->nextPageUrl() }}">次のページ</a></li>
                            {{-- @endif --}}
                        {{-- </ul>
                    </nav>
                </div> 
            </div> --}}
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
                crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/editorjs-html@3.4.0/build/edjsHTML.js"></script>
    <script>
        $(function () {
            let memoData = @json($hit_memos->pluck('memo_data') ?? []);
            let memoLength = memoData.length;

            for(let index=0;index<memoLength;index++) {
                let data = memoData[index];
                data = JSON.parse(data);
                let post_time = data.time;
                post_time = new Date(post_time);
                data = jsonConvertHtml(data);
                let data_id = `#data_${index}`;
                $(data_id).html(data);
                post_time = getStringFromDate(post_time);
                let time_id = `#time_${index}`;
                $(time_id).text(post_time);
            } 
        });

        function getStringFromDate(date) {
        
            let year_str = date.getFullYear();
            let month_str = 1 + date.getMonth();
            let day_str = date.getDate();
            let hour_str = date.getHours();
            let minute_str = date.getMinutes();
            let second_str = date.getSeconds();
            
            
            format_str = 'YYYY年MM月DD日 hh時mm分ss秒に投稿';
            format_str = format_str.replace(/YYYY/g, year_str);
            format_str = format_str.replace(/MM/g, month_str);
            format_str = format_str.replace(/DD/g, day_str);
            format_str = format_str.replace(/hh/g, hour_str);
            format_str = format_str.replace(/mm/g, minute_str);
            format_str = format_str.replace(/ss/g, second_str);
            
            return format_str;
        };

        function jsonConvertHtml(jsonData) {
            const edjsParser = edjsHTML();
            let html = edjsParser.parse(jsonData);
            return html;
        }
    </script>
</body>
</html>