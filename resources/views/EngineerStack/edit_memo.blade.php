<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>メモ編集 EngineerStack</title>
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
                        <form action="{{ route('memos.search') }}" method="GET">
                            @csrf 
                            <input class="input is-success" type="text" name="search_word" placeholder="キーワードで検索" required>
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
    <section class="content">
        <div class="form p-5">
            <div class="columns">
                <div class="column"></div>
                <div class="column is-three-fifths">
                    <div class="title mt-5">
                        <h4 class="is-size-4">メモを編集する</h4>
                        <div class="errors">
                            @if ($errors->any())
                                <div class="has-text-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                    <form action="{{ route('memos.update') }}" method="POST">
                        @csrf 
                        <input type="hidden" id="memo_data" name="memo_data">
                        <input type="hidden" name="memo_id" value="{{ $memo->id }}">
                        <input type="hidden" id="categories_count" name="categories_count">
                        <input type="hidden" id="memo_count" name="memo_count">
                        <div class="field">
                            <label for="comment">カテゴリ<br><span class="has-text-danger">*必須 最大5個 1カテゴリ30文字まで</span></label><br>
                            <span class="has-text-primary" id="count_category">残り5個入力可能</span>
                            <span id="disp_category"></span>
                            <div class="control has-text-centered">
                                <div class="field">
                                    <div class="control">
                                        <input name="categories" id="category" type="text" class="input is-success" placeholder="カテゴリ">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="memo">
                            <label for="editorjs">メモ<br><span class="has-text-danger">*必須</span></label>
                            <div id="editorjsCnt"></div>
                            <div id="editorjs" style="border: 1px solid #00d1b2; border-radius: 4px;"></div>
                        </div>
                        <button id="post_memo" class="button is-primary m-2">
                            <i class="far fa-edit"></i>更新
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
        $(function() {
            let memoData = @json($memo['memo_data']);
            memoData = JSON.parse(memoData);
            let categories = @json($categories);
            $('#category').val(categories);

            const editor = new EditorJS({
                minHeight: 50,
                holder: 'editorjs',
                tools: {
                    header: {
                        class: Header, 
                        inlineToolbar: ['link'] 
                    },        
                    list: List,        
                    quote: Quote,
                    code: CodeTool,
                },
                data: memoData,
            });

            $("#post_memo").click(function() {
                let editorjsTxt = getEditorjsTxt();
                let cntTxt = getEditorjsCnt(editorjsTxt);
                let categories = $('#category').val();
                let categoriesArr = separateText(',', categories);
                let categories_count = categoriesArr.length;

                $('#categories_count').val(categories_count);
                $('#memo_count').val(cntTxt);

                editor.save().then((outputData) => {
                    $('#memo_data').val(JSON.stringify(outputData));
                }).catch((error) => {

                });
            });

            $("#category").keyup(function() {
                const separator = ",";
                let inputText = $(this).val();
                let textToArray = separateText(separator, inputText);
                let dispText = arrayToText(textToArray);
                let tags = pushTag(textToArray);
                $("#disp_category").html(tags);
                let arrayLength = textToArray.length;
                $('#categories_count').val(arrayLength);
            });
        });

        //カテゴリの関数
        function separateText(separator, text) {
            let splitText = text.split(separator);
            return splitText;
        }

        function arrayToText(array) {
            let text = array.join(" ");
            return text;
        }

        function countCategory(categoryArray) {
            let length = categoryArray.length;
            let remain = 5 - length;
            const id = "#count_category";

            if(remain > 0) {
                const text = "残り" + remain + "個入力可能";
                changeText(id, text);
            } else if(remain === 0){
                const text = "入力できる最大数です。";
                changeText(id, text);
            } else {
                const isNormal = false;
                const text = "最大数を超えています!";
                changeText(id, text);
                changeClass(id, isNormal);
            }
        }

        function createElement(tag, type, text) {
            let element = $(`<${tag}>`, {class:type, text: text});
            return element;
        }

        function pushTag(array) {
            let tags = [];
            //カテゴリの要素数が5より上の場合は処理を中断
            if(array == undefined) {
                return;
            } else if(array.length > 5) {
                return;
            }
            for(let i=0; i<array.length; i++) {
                let element = createElement("i", "fas fa-tape tag is-primary mr-1 mb-1", array[i]);
                tags.push(element);
            }
            return tags;
        }
        //ここまで

        //タイトルとメモの関数
        function countText(id, limit, length) {
            let remain = limit -length;
            if(remain > 0) {
                const isNormal = true;
                const text = "残り" + remain + "文字入力可能";
                changeText(id, text);
                changeClass(id, isNormal);
            } else if(remain === 0) {
                const isNormal = true;
                const text = "入力できる最大文字数です。";
                changeText(id, text);
                changeClass(id, isNormal);
            } else {
                const isNormal = false;
                const text = "最大文字数を超えています！";
                changeText(id, text);
                changeClass(id, isNormal);
            }
        }
        //ここまで

        //共通の関数
        function changeClass(id, isNormal) {
            let normalStatus = "has-text-primary";
            let abnormalStatus = "has-text-danger";
            
            if(isNormal) {
                $(id).attr("class", normalStatus);
            } else {
                $(id).attr("class", abnormalStatus);
            }
        }

        function changeText(id, text) {
            $(id).text(text);
        }
        //ここまで
        function getEditorjsTxt() {
            let myCode = $('#editorjs').html();
            let cleanCode = myCode.replace(/<(?:.|\n)*?>/gm, '').replace(/(\r\n|\n|\r)/gm,"").replace('&nbsp;','');
            cleanCode = cleanCode.slice(0, -52);
            return cleanCode;
        }

        function getEditorjsCnt(text) {
            let numChars = text.length;
            if(numChars < 0) {
                numChars = 0;
            }
            $('#editorjsCnt').text(numChars + "文字入力されています。");
            return numChars;
        }
    </script>
</body>
</html>