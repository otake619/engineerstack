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
     * 
     * @return array $memos
     * $user_idに該当するアカウントの全メモデータ。
     */
    public function index()
    {
        $user_id = Auth::id();
        //後でカテゴリーの参照とViewへの返却も実装
        $memos = Memo::where('user_id', $user_id)->get();
        $memo_data = Memo::where('user_id', $user_id)->get('memo_data');
        return view('EngineerStack.home', compact('memos', 'memo_data'));
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
        //$categories: 今は使わないが後ほど使用
        //後ほどCategoriesServiceとCategoryControllerと連携して
        //カンマ区切りで1つずつ分けてDBへ保存する処理を実装
        $categories = $request->input('categories');
        $title = $request->input('title');
        $memo_data = $request->input('memo_data');
        $insert_memo = Memo::store($user_id, $title, $memo_data);
        return view('EngineerStack.detailed_memo', compact('memo_data',
                                            'title', 'categories'));
    }

    /**
     * 指定memoレコードの編集
     * @param  int  $id
     * TODO: 後ほど、user_idが異なるアカウントでredirectが
     * 発動するかテスト
     */
    public function edit($id)
    {
        $memo_id = $id;
        $memo = Memo::find($memo_id);
        $memo_owner = $memo->user_id;
        if($memo_owner != Auth::id()) {
            redirect()->route('dashboard');
        } else {
            $memo_data = $memo['memo_data'];
            return view('EngineerStack.edit_memo'
                    , compact('memo', 'memo_data'));
        }
    }

    /**
     * 指定memoレコードの更新
     * @param  int  $id
     * 
     */
    public function update(Request $request)
    {
        $memo_id = $request->input('memo_id');
        $memo_data = $request->input('memo_data');
        $memo_owner = Memo::find($memo_id)->user_id;
        if($memo_owner != Auth::id()) {
            redirect()->route('dashboard');
        } else {
            //更新処理
            Memo::where('id', $memo_id)->update(['memo_data' => $memo_data]);
            $title = Memo::find($memo_id)->title;
            //TODO: カテゴリ機能実装時に必ず修正。
            $categories = "php, Laravel, MVC, EngineerStack";
            $memo_data = Memo::find($memo_id)->memo_data;
            return view('EngineerStack.detailed_memo'
                    , compact('title', 'categories', 'memo_data', 'memo_id'));
        }
    }

    public function show(Request $request)
    {
        $memo_id = $request->input('memo_id');
        $memo_data = $request->input('memo_data');
        $memo_owner = Memo::find($memo_id)->user_id;
        if($memo_owner != Auth::id()) {
            redirect()->route('dashboard');
        } else {
            $title = Memo::find($memo_id)->title;
            //TODO: カテゴリ機能実装時に必ず修正。
            $categories = "php, Laravel, MVC, EngineerStack";
            $memo_data = Memo::find($memo_id)->memo_data;
            return view('EngineerStack.detailed_memo',
                    compact('title', 'categories', 'memo_data', 'memo_id'));
        }
    }

    /**
     * 指定memoレコードの削除
     * @param  int  $id
     */
    public function destroy($id)
    {

    }
}
