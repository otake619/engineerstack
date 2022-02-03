<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Memo;
use Illuminate\Http\Request;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    private $category;

    /**
     * 認証前ではカテゴリーに関するデータにはアクセス不可
     * @param App\Services\CategoryService $categoryService カテゴリーのサービスクラス
     * @return void
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->middleware('auth');
        $this->category = $categoryService;
    }

    /**
     * カテゴリーの全権取得
     * @return Illuminate\View\View 
     * @return Illuminate\View\View カテゴリ一覧画面
     */
    public function index()
    {
        $user_id = Auth::id();
        $categories = Category::where('user_id', $user_id)->get();
        return view('EngineerStack.all_categories', compact('categories'));
    }

    /**
     * カテゴリーの保存
     * @param string $categories メモ記録画面で入力されたカテゴリ
     * @param int $memo_id メモのid
     * @return void
     */
    public function store(string $categories, int $memo_id)
    {
        $insert_categories = $this->category->insertCategoryMemos($categories, $memo_id);
        return $insert_categories;
    }

    /**
     * メモに対応するカテゴリを取得
     * @param object $memos
     * memoのcollection
     * @return object $categories
     * categoryのcollection
     */
    public function getCategories(object $memos)
    {
        $category_arr = $this->category->getCategories($memos);
        return $category_arr;
    }
}
