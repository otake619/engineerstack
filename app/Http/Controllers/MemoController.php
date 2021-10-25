<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
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
     * ToDo 後でStoreMemoRequestに型を書き換えて、フォームバリデーションを
     * 実装
     */
    public function store(Request $request)
    {
        $user_id = Auth::id();
        $categories = $request->input('categories');
        $title = $request->input('title');
        $memo_data = $request->input('memo_data');
        $insert_memo = Memo::store($user_id, $title, $memo_data);
        return view('EngineerStack.input_memo');
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
