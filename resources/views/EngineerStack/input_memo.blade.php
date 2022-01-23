<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>メモ記録 EngineerStack</title>
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
        @if (session('message'))
            <div class="notification is-danger has-text-centered">
                {{ session('message') }}
            </div>
        @endif
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
                        <div class="field">
                            <label for="comment">カテゴリ <span class="has-text-danger">半角カンマ「,」で区切って入力</span><br><span class="has-text-danger">*必須 最大5個 カテゴリ1つにつき20文字以内</span></label><br>
                            <span class="has-text-primary" id="count_category">残り5個入力可能</span>
                            <span class="has-text-danger" id="flag"></span><br>
                            <span class="is-primary" id="disp_category"></span>
                            <div class="control has-text-centered">
                                <div class="field">
                                    <div class="control">
                                        <input type="text" name="categories" class="input is-hovered" id="category" placeholder="カテゴリ1, カテゴリ2, カテゴリ3,..." required autofocus>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="editor_field">
                            <label for="memo">メモ<br><span class="has-text-danger">*必須 5000文字以内</span></label>
                            <p id="memo-count"></p>
                            <textarea name="memo" id="memo" class="textarea is-hovered" cols="70" rows="15"></textarea>
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
            $("#category").keyup(function() {
                let flgArr = []
                const separator = ",";
                let inputText = $(this).val();
                let textToArray = separateText(separator, inputText);
                countCategory(textToArray);
                textToArray.forEach(function(element, index) {
                    let length = Math.max(...element.split(" ").map (element => element.length));
                    if(length <= 20) {
                        flgArr[index] = true;
                    } else {
                        flgArr[index] = false;
                    }
                });
                let dispText = arrayToText(textToArray);
                let tags = pushTag(textToArray);
                $("#disp_category").html(tags);
                let arrayLength = textToArray.length;
                $('#categories_count').val(arrayLength);
                if(flgArr.includes(false)) {
                    $('#category_flg').val(false);
                    $('#flag').text('20文字以上のカテゴリは設定できません。');
                } else {
                    $('#category_flg').val(true);
                    $('#flag').text('');
                }
            });

            $("#memo").keyup(function() {
                let memoCount = $(this).val().length;

                if(memoCount <= 5000) {
                    $("#memo-count").text(memoCount + "文字");
                    changeClass("#memo-count", true);
                } else {
                    $("#memo-count").text("5000文字を超えています!");
                    changeClass("#memo-count", false);
                }
            });
        });

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
    </script>
</body>
</html>