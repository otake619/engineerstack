<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>EngineerStack メモ一覧</title>
</head>
<body>
    <section class="header">
        <nav class="navbar" role="navigation" aria-label="main navigation">
            <div class="navbar-brand">
                <a class="navbar-item is-size-3 has-text-weight-semibold has-text-primary" href="{{ route('admin.dashboard') }}">
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
                            <form action="{{ route('admin.logout') }}" method="POST">
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
        <h4 class="title has-text-centered">メモ一覧</h4>
        <div class="columns">
            <div class="select-database column is-one-quarter m-3">
                <a href="{{ route('admin.dashboard') }}">
                    <button class="button is-primary is-outlined is-fullwidth"><i class="fas fa-home"></i>ホーム</button>
                </a>
                <a href="{{ route('admin.get.users') }}">
                    <button class="button is-primary is-outlined is-fullwidth mt-3"><i class="fas fa-users"></i>ユーザー一覧</button>
                </a>
                <a href="{{ route('admin.get.categories') }}">
                    <button class="button is-primary is-outlined is-fullwidth mt-3"><i class="fas fa-tape"></i>カテゴリ一覧</button>
                </a>
                <a href="{{ route('admin.get.memos') }}">
                    <button class="button is-primary is-outlined is-fullwidth mt-3"><i class="fas fa-file-code"></i>メモ一覧</button>
                </a>
            </div>
            <div class="server-info column is-three-quarter">
                <div class="user-info column is-three-quarter">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>
                                    id
                                </th>
                                <th>
                                    user_id
                                </th>
                                <th>
                                    memo
                                </th>
                                <th>
                                    作成日
                                </th>
                                <th>
                                    更新日
                                </th>
                                <th>
                                    削除日
                                </th>
                                <th>
                                    削除
                                </th>
                            </tr>
                            @if($memos->isEmpty())
                                <div class="notification is-warning has-text-centered">
                                    メモの登録がありません。
                                </div>
                            @else
                                @foreach ($memos as $memo)
                                    <tr>
                                        <th>
                                            {{ $memo->id }}
                                        </th>
                                        <th>
                                            {{ $memo->user_id }}
                                        </th>
                                        <th>
                                            {{ $memo->memo }}
                                        </th>
                                        <th>
                                            {{ $memo->created_at }}
                                        </th>
                                        <th>
                                            {{ $memo->updated_at }}
                                        </th>
                                        <th>
                                            {{ $memo->deleted_at }}
                                        </th>
                                        <th>
                                            <form action="{{ route('admin.delete.memo') }}" method="POST">
                                                @csrf
                                                <input type="hidden" value="{{ $memo->id }}" name="memo_id">
                                                <button type="submit" style="background-color: transparent; border: none;">
                                                    <i class="fas fa-trash-alt has-text-danger">
                                                    </i>
                                                </button>
                                            </form>
                                        </th>
                                    </tr>
                                @endforeach
                            @endif 
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    @if($memos->isEmpty())
    @else 
        <section class="paging">
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
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/navbar.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"
                integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
                crossorigin="anonymous"></script>
</body>
</html>