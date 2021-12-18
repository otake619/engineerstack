<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Memo;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Exception;

class AdminMemoController extends Controller
{
    /**
     * コンストラクタでmiddleware('auth:admin');
     * を設定しているので、ログイン前では
     * メモ・ユーザーに関するデータにはアクセスできません。
     * 
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * メモの管理画面で、ページネーションを使用して
     * 1画面につき50件のカテゴリを返します。
     * 
     * @return Illuminate\View\View
     * メモ管理画面を返します。
     */
    public function index()
    {
        $memos = Memo::paginate(50);
        return view('admin.admin_memos', compact('memos'));
    }

    /**
     * この関数はメモ一件を削除する処理を担当しています。
     * @param Illuminate\Http\Request $request
     * $requestには、
     * メモのid
     * が含まれています。
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     * メモ管理画面を返します。
     */
    public function destroy(Request $request)
    {
        $memo_id = $request->input('memo_id');
        Memo::destroy($memo_id);

        return redirect()->route('admin.get.memos');
    }

}
