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

class AdminUserController extends Controller
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
     * ユーザーの管理画面で、ページネーションを使用して
     * 1画面につき50件のユーザーを返します。
     * 
     * @return Illuminate\View\View
     * ユーザー管理画面を返します。
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('admin.admin_users', compact('users'));
    }

    /**
     * この関数はユーザー一件を削除する処理を担当しています。
     * @param Illuminate\Http\Request $request
     * $requestには、
     * ユーザーのid
     * が含まれています。
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     * ユーザー管理画面を返します。
     */
    public function destroy(Request $request)
    {
        $user_id = $request->input('user_id');
        User::destroy($user_id);

        return redirect()->route('admin.get.users');
    }

}
