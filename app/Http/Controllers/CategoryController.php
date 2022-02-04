<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Category;
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
     * カテゴリーの一覧取得
     * @return Illuminate\View\View カテゴリー一覧画面
     */
    public function index()
    {
        $user_id = Auth::id();
        $categories = Category::where('user_id', $user_id)->get();
        return view('EngineerStack.all_categories', compact('categories'));
    }

    /**
     * カテゴリーの保存
     * @param string $categories カテゴリーのname
     * @param int $memo_id メモのID
     * @return int $count_category カテゴリーの要素数
     */
    public function store(string $categories, int $memo_id)
    {
        $count_category = $this->category->insertCategoryMemos($categories, $memo_id);
        return $count_category;
    }

    /**
     * メモに対応するカテゴリを取得
     * @param Illuminate\Support\Collection $memos メモのcollection
     * @return Illuminate\Support\Collection $categories カテゴリーのcollection
     */
    public function getCategories(object $memos)
    {
        $categories = $this->category->getCategories($memos);
        return $categories;
    }
}
