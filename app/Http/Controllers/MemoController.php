<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreMemoRequest;
use App\Models\Memo;
use App\Models\Category;

class MemoController extends Controller
{
    /**
     * 新しいmemoインスタンスの作成
     * 
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 全memoレコードの取得
     */
    public function index()
    {

    }

    public function create()
    {

    }

    /**
     * memoレコードを1件DBに保存
     * 
     * @param  \App\Http\Requests\StoreMemoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMemoRequest $request)
    {
        $user_id = Auth::id();
        $title = $request->input('title');
        $store_memo = App\Memo::store(int $user_id, string $title);
        $memo_id = DB::table('memos')->insertGetId($store_memo);
        $categories = $request->input('categories');
        $store_categories = app()->make('App\Http\Controllers\CategoryController');
        $store_categories->store(array $categories, int $user_id, int $memo_id);
        $json = $request->input('json');
        $store_content = app()->make('App\Http\Controllers\ContentController');
        $store_content->store(array $json, int $user_id, int $memo_id);
        return view('EngineerStack.home');
    }

    /**
     * 指定memoレコードの編集
     * @param  int  $id
     */
    public function edit($id)
    {

    }

    /**
     * 指定memoレコードの更新
     * @param  int  $id
     */
    public function update($id)
    {

    }

    /**
     * 指定memoレコードの削除
     * @param  int  $id
     */
    public function destroy($id)
    {

    }
}
