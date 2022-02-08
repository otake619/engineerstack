<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>プライバシーポリシー EngineerStack</title>
</head>
<body>
    @guest
        <section class="header">
            <nav class="navbar" role="navigation" aria-label="main navigation">
                <div class="navbar-brand">
                    <a class="navbar-item is-size-3 has-text-weight-semibold has-text-primary" href="{{ route('login') }}">
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
                                <a class="button is-primary" href="{{ route('register') }}">
                                    <strong>アカウント作成</strong>
                                </a>
                                <a class="button is-light" href="{{ route('login') }}">
                                    ログイン
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </section>
    @else 
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
                                            <input class="input is-success is-6" type="text" name="search_word" placeholder="メモ本文を検索" required>
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
    @endguest
    <section class="content has-background-primary m-5 p-5">
        <div class="guidelines">
            <h1 class="has-text-white">プライバシーポリシー</h1>
            <p class="has-text-white">EngineerStack（以下「当方」といいます）は、以下のとおり個人情報保護方針を定め、個人情報の保護を推進致します。</p>
            <h1 class="has-text-white">個人情報保護の管理</h1>
            <p class="has-text-white">当方は、ユーザーの個人情報を正確かつ最新の状態に保ち、個人情報への不正アクセス・紛失・破損・改ざん・漏洩などを防止するため、セキュリティシステムの維持等の必要な措置を講じ、安全対策を実施し個人情報の厳重な管理を行ないます。</p>
            <h1 class="has-text-white">個人情報の利用目的</h1>
            <p class="has-text-white">ユーザーからお預かりした個人情報は、当方でのサービス運営のために利用いたします。</p>
            <ul class="has-text-white">
                <li>ユーザー数の把握</li>
                <li>利用規約に違反したユーザーアカウントの凍結</li>
            </ul>
            <p class="has-text-white">等の目的に利用いたします。</p>
            <h1 class="has-text-white">個人情報の第三者への開示・提供の禁止</h1>
            <p class="has-text-white">当方は、ユーザーよりお預かりした個人情報を適切に管理し、次のいずれかに該当する場合を除き、個人情報を第三者に開示いたしません。
                <ul class="has-text-white">
                    <li>お客さまの同意がある場合</li>
                    <li>法令に基づき開示することが必要である場合</li>
                </ul>
            </p>
            <h1 class="has-text-white">個人情報の安全対策</h1>
            <p class="has-text-white">当方は、個人情報の正確性及び安全性確保のために、セキュリティに万全の対策を講じています。</p>
            <h1 class="has-text-white">ご本人の照会</h1>
            <p class="has-text-white">ユーザーがご本人の個人情報の照会・修正・削除などをご希望される場合には、ご本人であることを確認の上、対応させていただきます。</p>
            <h1 class="has-text-white">法令、規範の遵守と見直し</h1>
            <p class="has-text-white">当方は、保有する個人情報に関して適用される日本の法令、その他規範を遵守するとともに、本ポリシーの内容を適宜見直し、その改善に努めます。</p>
            <h1 class="has-text-white">お問い合せ</h1>
            <p class="has-text-white">当方の個人情報の取扱に関するお問い合せはお問い合わせフォームにてご連絡ください。</p>
            <br><br>
        </div>
    </section>
    <section class="footer">
        <div class="columns">
            <div class="column">
                <a class="navbar-item is-size-5 has-text-weight-semibold has-text-primary" href="{{ route('login') }}">
                    EngineerStack
                </a>
                <span class="m-3">&copy;otake619 2021</span>
            </div>
            <div class="column m-3">
                <p class="mb-3">EngineerStack</p>
                <a class="has-text-primary" href="{{ route('guidelines') }}">利用規約</a><br>
                <a class="has-text-primary" href="{{ route('privacy_policy') }}">プライバシーポリシー</a><br>
                <a class="has-text-primary" href="{{ route('contact.index') }}">お問い合わせ</a>
            </div>
        </div>
    </section>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/navbar.js') }}"></script>
</body>
</html>