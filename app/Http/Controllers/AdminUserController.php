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
     * 認証前はアクセス不可。
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * ユーザーの管理画面で、ページネーションを使用して
     * 1ページにつき10件のユーザーを返します。
     * @return Illuminate\View\View ユーザー管理画面
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('admin.admin_users', compact('users'));
    }

    /**
     * ユーザーの削除。
     * @param Illuminate\Http\Request $request ユーザーID
     * @return \Illuminate\Http\RedirectResponse ユーザー管理画面
     */
    public function destroy(Request $request)
    {
        $user_id = $request->input('user_id');
        User::destroy($user_id);
        return redirect()->route('admin.get.users');
    }

}
