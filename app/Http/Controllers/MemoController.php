<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
     */
    public function store()
    {

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
