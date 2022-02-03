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
     * 認証前はアクセス不可。
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * カテゴリの管理画面で、ページネーションを使用して
     * 1ページにつき10件のカテゴリを返す。
     * @return Illuminate\View\View カテゴリ管理画面
     */
    public function index()
    {
        $categories = Category::paginate(10);
        return view('admin.admin_categories', compact('categories'));
    }

    /**
     * カテゴリーの削除。
     * @param Illuminate\Http\Request $request カテゴリーのID
     * @return \Illuminate\Http\RedirectResponse カテゴリーの管理画面
     */
    public function destroy(Request $request)
    {
        $category_id = $request->input('category_id');
        Category::destroy($category_id);
        return redirect()->route('admin.get.categories');
    }

}
