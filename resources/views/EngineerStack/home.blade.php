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
                <div class="field mt-4 ml-5">
                    <div class="control has-icons-left has-icons-right">
                        <input class="input is-success" type="text" name="search_word" placeholder="キーワードを入力">
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
    @if(isset($memos))
        <section class="content">
            <div class="title has-text-centered m-5">
                <p class="is-size-4 is-text-weight-bold">最近のメモ</p>
            </div>
            <div class="columns">
                <div class="column is-one-thirds">
                    <div class="category p-5">
                        <span class="tag"><i class="fas fa-tape"></i>php</span><br>
                        <span class="tag"><i class="fas fa-tape"></i>Laravel</span><br>
                        <span class="tag"><i class="fas fa-tape"></i>SQL</span><br>
                        <span class="tag"><i class="fas fa-tape"></i>MVC</span><br>
                        <span class="tag"><i class="fas fa-tape"></i>CRUD</span><br>
                        <span class="tag is-primary"><i class="fas fa-tape"></i>つまづきポイント</span><br>
                        <span class="tag"><i class="fas fa-tape"></i>悩んだ点</span><br>
                        <span class="tag"><i class="fas fa-tape"></i>解決策</span><br>
                        <span class="tag"><i class="fas fa-tape"></i>思考プロセス</span><br>
                        <span class="tag"><i class="fas fa-tape"></i>javascript</span><br>
                        <span class="tag"><i class="fas fa-tape"></i>qiita</span><br>
                        <span class="tag"><i class="fas fa-tape"></i>zenn</span><br>
                        <span class="tag"><i class="fas fa-tape"></i>気づき</span><br>
                        <span class="tag"><i class="fas fa-tape"></i>アイデア</span><br>
                    </div>
                </div>
                <div class="column is-one-thirds">
                    <div class="memos">
                        <div class="memo box column m-3">
                            <div class="category">
                                <span class="tag is-primary"><i class="fas fa-tape"></i>つまづきポイント</span>
                                <span class="tag"><i class="fas fa-tape"></i>ログ</span>
                                <span class="tag"><i class="fas fa-tape"></i>思考プロセス</span>
                                <span class="tag"><i class="fas fa-tape"></i>php</span>
                                <span class="tag"><i class="fas fa-tape"></i>Laravel</span>
                                <span class="tag"><i class="fas fa-tape"></i>MVC</span>
                            </div><br>
                            <div class="title">
                                <h4 class="is-size-5 has-text-weight-bold has-text-link">{{ $memos[0]->title }}</h4>
                            </div>
                            <div id="data_0">
                            </div><br>
                            <div class="post-time">
                                <p id="time_0"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-one-thirds">
                    <div class="memos">
                        <div class="memo box column m-3">
                            <div class="category">
                                <span class="tag is-primary"><i class="fas fa-tape"></i>つまづきポイント</span>
                                <span class="tag"><i class="fas fa-tape"></i>ログ</span>
                                <span class="tag"><i class="fas fa-tape"></i>思考プロセス</span>
                                <span class="tag"><i class="fas fa-tape"></i>php</span>
                                <span class="tag"><i class="fas fa-tape"></i>Laravel</span>
                                <span class="tag"><i class="fas fa-tape"></i>MVC</span>
                            </div><br>
                            <div class="title">
                                <h4 class="is-size-5 has-text-weight-bold has-text-link">{{ $memos[1]->title }}</h4>
                            </div>
                            <div id="data_1">
                            </div><br>
                            <div class="post-time">
                                <p id="time_1"></p>
                            </div>
                        </div>
                        <div class="memo box column m-3">
                            <div class="category">
                                <span class="tag is-primary"><i class="fas fa-tape"></i>つまづきポイント</span>
                                <span class="tag"><i class="fas fa-tape"></i>ログ</span>
                                <span class="tag"><i class="fas fa-tape"></i>思考プロセス</span>
                                <span class="tag"><i class="fas fa-tape"></i>php</span>
                                <span class="tag"><i class="fas fa-tape"></i>Laravel</span>
                                <span class="tag"><i class="fas fa-tape"></i>MVC</span>
                            </div><br>
                            <div class="title">
                                <h4 class="is-size-5 has-text-weight-bold has-text-link">{{ $memos[2]->title }}</h4>
                            </div>
                            <div id="data_2">
                            </div><br>
                            <div class="post-time">
                                <p id="time_2"></p>
                            </div>
                        </div>
                        <div class="memo box column m-3">
                            <div class="category">
                                <span class="tag is-primary"><i class="fas fa-tape"></i>つまづきポイント</span>
                                <span class="tag"><i class="fas fa-tape"></i>ログ</span>
                                <span class="tag"><i class="fas fa-tape"></i>思考プロセス</span>
                                <span class="tag"><i class="fas fa-tape"></i>php</span>
                                <span class="tag"><i class="fas fa-tape"></i>Laravel</span>
                                <span class="tag"><i class="fas fa-tape"></i>MVC</span>
                            </div><br>
                            <div class="title">
                                <h4 class="is-size-5 has-text-weight-bold has-text-link">{{ $memos[3]->title }}</h4>
                            </div>
                            <div id="data_3">
                            </div><br>
                            <div class="post-time">
                                <p id="time_3"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <div class="paging has-text-centered mt-5">
                        <a href="">1</a>
                        <a href="">2</a>
                        <a href="">3</a>
                        <a href="">4</a>
                        <a class="ml-5" href="">次へ</a>
                    </div>
                </div>
            </div>
        </section>
    @else 
        <section class="content">

        </section>
    @endisset
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
            let memoData = @json($memo_data);

            for(let index=0;index<4;index++) {
                let data = memoData[index];
                data = data['memo_data'];
                data = JSON.parse(data);
                let post_time = data.time;
                post_time = new Date(post_time);
                data = jsonConvertHtml(data);
                let data_id = `#data_${index}`;
                $(data_id).html(data);
                console.log(post_time);
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