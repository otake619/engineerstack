<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Memo;
use App\Models\CategoryMemo;

class CategoryMemosController extends Controller
{
    /**
     * 認証前はアクセス不可。
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 中間テーブルにメモとカテゴリーを紐づけるレコードを保存。
     * @param int $memo_id メモのID
     * @param int $category_id カテゴリーのID
     * @return void
     */
    public function store(int $memo_id, int $category_id)
    {
        $is_exist = CategoryMemo::where('memo_id', $memo_id)
                                ->where('category_id', $category_id)->exists();
        if(!$is_exist) {
            CategoryMemo::store($memo_id, $category_id);
        }
    }
}
