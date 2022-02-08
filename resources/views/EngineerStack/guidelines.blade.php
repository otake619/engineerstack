<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>利用規約 EngineerStack</title>
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
            <h1 class="has-text-white">利用規約</h1>
            <p class="has-text-white">この利用規約（以下，「本規約」といいます。）は，EngineerStack（以下，「当方」といいます。）がこのウェブサイト上で提供するサービス（以下，「本サービス」といいます。）の利用条件を定めるものです。登録ユーザーの皆さま（以下，「ユーザー」といいます。）には，本規約に従って，本サービスをご利用いただきます。</p>
            <h1 class="has-text-white">第1条（適用）</h1>
            <p class="has-text-white">1.本規約は，ユーザーと当方との間の本サービスの利用に関わる一切の関係に適用されるものとします。</p>
            <p class="has-text-white">2.当方は本サービスに関し，本規約のほか，ご利用にあたってのルール等，各種の定め（以下，「個別規定」といいます。）をすることがあります。これら個別規定はその名称のいかんに関わらず，本規約の一部を構成するものとします。</p>
            <p class="has-text-white">3.本規約の規定が前条の個別規定の規定と矛盾する場合には，個別規定において特段の定めなき限り，個別規定の規定が優先されるものとします。</p>
            <h1 class="has-text-white">第2条（利用登録)</h1>
            <p class="has-text-white">1.本サービスにおいては，登録希望者が本規約に同意の上，運営元の定める方法によって利用登録を申請し，当方がこの承認を登録希望者に通知することによって，利用登録が完了するものとします。</p>
            <p class="has-text-white">2.当方は，利用登録の申請者に以下の事由があると判断した場合，利用登録の申請を承認しないことがあり，その理由については一切の開示義務を負わないものとします。</p>
            <ol class="has-text-white">
                <li>利用登録の申請に際して虚偽の事項を届け出た場合</li>
                <li>本規約に違反したことがある者からの申請である場合</li>
                <li>その他，当方が利用登録を相当でないと判断した場合</li>
            </ol>
            <h1 class="has-text-white">第3条（ユーザーIDおよびパスワードの管理）</h1>
            <ol class="has-text-white">
                <li>ユーザーは，自己の責任において，本サービスのユーザーIDおよびパスワードを適切に管理するものとします。</li>
                <li>ユーザーは，いかなる場合にも，ユーザーIDおよびパスワードを第三者に譲渡または貸与し，もしくは第三者と共用することはできません。当方は，ユーザーIDとパスワードの組み合わせが登録情報と一致してログインされた場合には，そのユーザーIDを登録しているユーザー自身による利用とみなします。</li>
                <li>ユーザーID及びパスワードが第三者によって使用されたことによって生じた損害は，当方に故意又は重大な過失がある場合を除き，当方は一切の責任を負わないものとします。</li>
            </ol>
            <h1 class="has-text-white">第4条（禁止事項）</h1>
            <ol class="has-text-white">
                <li>法令または公序良俗に違反する行為</li>
                <li>犯罪行為に関連する行為</li>
                <li>当方，本サービスの他のユーザー，または第三者のサーバーまたはネットワークの機能を破壊したり，妨害したりする行為</li>
                <li>当方のサービスの運営を妨害するおそれのある行為</li>
                <li>他のユーザーに関する個人情報等を収集または蓄積する行為</li>
                <li>不正アクセスをし，またはこれを試みる行為</li>
                <li>他のユーザーに成りすます行為</li>
                <li>当方，本サービスの他のユーザーまたは第三者の知的財産権，肖像権，プライバシー，名誉その他の権利または利益を侵害する行為</li>
                <li>その他，当方が不適切と判断する行為</li>
            </ol>
            <h1 class="has-text-white">第5条（利用制限および登録抹消）</h1>
            <ol class="has-text-white">
                <li>当方は，ユーザーが以下のいずれかに該当する場合には，事前の通知なく，投稿データを削除し，ユーザーに対して本サービスの全部もしくは一部の利用を制限しまたはユーザーとしての登録を抹消することができるものとします。</li>
                <ol>
                    <li>本規約のいずれかの条項に違反した場合</li>
                    <li>その他，当方が本サービスの利用を適当でないと判断した場合</li>
                </ol>
            </ol>
            <h1 class="has-text-white">第6条（退会）</h1>
            <p class="has-text-white">ユーザーは，当方の定める退会手続により，本サービスから退会できるものとします。</p>
            <h1 class="has-text-white">第7条（サービス内容の変更等）</h1>
            <p class="has-text-white">当方は，ユーザーへの事前の告知をもって、本サービスの内容を変更、追加または廃止することがあり、ユーザーはこれを承諾するものとします。</p>
            <h1 class="has-text-white">第8条（利用規約の変更）</h1>
            <ol class="has-text-white">
                <li>
                    当方は以下の場合には、ユーザーの個別の同意を要せず、本規約を変更することができるものとします。
                    <ol>
                        <li>本規約の変更がユーザーの一般の利益に適合するとき。</li>
                        <li>本規約の変更が本サービス利用契約の目的に反せず、かつ、変更の必要性、変更後の内容の相当性その他の変更に係る事情に照らして合理的なものであるとき。</li>
                    </ol>
                </li>
                <li>当方はユーザーに対し、前項による本規約の変更にあたり、事前に、本規約を変更する旨及び変更後の本規約の内容並びにその効力発生時期を通知します。</li>
            </ol>
            <h1 class="has-text-white">第9条（個人情報の取扱い）</h1>
            <p class="has-text-white">当方は，本サービスの利用によって取得する個人情報については，当方「プライバシーポリシー」に従い適切に取り扱うものとします。</p>
            <h1 class="has-text-white">第10条（準拠法・裁判管轄）</h1>
            <ol class="has-text-white">
                <li>本規約の解釈にあたっては，日本法を準拠法とします。</li>
                <li>本サービスに関して紛争が生じた場合には，当方の所在地を管轄する裁判所を専属的合意管轄とします。</li>
            </ol>
            <p class="has-text-white is-pulled-right">以上</p>
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