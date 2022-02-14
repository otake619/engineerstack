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
     * 認証前はアクセス不可。
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * メモの管理画面で、ページネーションを使用して
     * 1ページにつき10件のカテゴリを返します。
     * @return Illuminate\View\View メモ管理画面
     */
    public function index()
    {
        $memos = Memo::withTrashed()->paginate(10);
        return view('admin.admin_memos', compact('memos'));
    }

    /**
     * メモの削除。
     * @param Illuminate\Http\Request $request メモのID
     * @return \Illuminate\Http\RedirectResponse メモの管理画面
     */
    public function destroy(Request $request)
    {
        $memo_id = $request->input('memo_id');
        Memo::destroy($memo_id);
        return redirect()->route('admin.get.memos');
    }

}
