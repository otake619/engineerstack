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
     * コンストラクタでmiddleware('auth');
     * を設定しているので、ログイン前では
     * カテゴリに関するデータにはアクセスできません。
     * @param $categoryService
     * カテゴリーに関するサービスクラス。
     * @return void
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->middleware('auth');
        $this->category = $categoryService;
    }

    /**
     * この関数にはメモ記録画面で入力されたカテゴリを受け取り
     * 1つずつカテゴリtableに保存する機能があります。
     *
     * @param string $categories : メモ記録画面で入力されたカテゴリ
     * @param int $memo_id : メモのid
     * @return void
     */
    public function store($categories, $memo_id)
    {
        $this->category->insertCategoryMemos($categories, $memo_id);
    }
}
