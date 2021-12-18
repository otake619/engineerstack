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

class AdminCategoryController extends Controller
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
     * カテゴリの管理画面で、ページネーションを使用して
     * 1画面につき50件のカテゴリを返します。
     * 
     * @return Illuminate\View\View
     * カテゴリ管理画面を返します。
     */
    public function index()
    {
        $categories = Category::paginate(50);
        return view('admin.admin_categories', compact('categories'));
    }

    /**
     * この関数はカテゴリ一件を削除する処理を担当しています。
     * @param Illuminate\Http\Request $request
     * $requestには、
     * カテゴリのid
     * が含まれています。
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     * カテゴリ管理画面を返します。
     */
    public function destroy(Request $request)
    {
        $category_id = $request->input('category_id');
        Category::destroy($category_id);

        return redirect()->route('admin.get.categories');
    }

}
