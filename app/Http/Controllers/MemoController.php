<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMemoRequest;
use App\Models\Memo;
use App\Models\Category;
use App\Services\MemoService;
use Illuminate\Support\Facades\DB;

class MemoController extends Controller
{
    private $memo;

    /**
     * コンストラクタでmiddleware('auth');
     * を設定しているので、ログイン前では
     * メモに関するデータにはアクセスできません。
     * 
     * @return void
     */
    public function __construct(MemoService $memoService)
    {
        $this->middleware('auth');
        $this->memo = $memoService;
    }

    /**
     * この関数はメモの全件取得を担当しています。
     * @param void
     * @return Illuminate\View\View
     * ホーム画面を返します。
     */
    public function index()
    {
        $user_id = Auth::id();
        //後でカテゴリーの参照とViewへの返却も実装
        $memos = Memo::where('user_id', $user_id)->get();
        //TODO $memosがある時点で、$memo_dataいらないのでは？後で検討。
        //他の関数でも変数の重複が見られるから、要検討。
        $categories = $this->memo->getCategories($memos);
        $memo_data = Memo::where('user_id', $user_id)->get('memo_data');
        return view('EngineerStack.home', compact('memos', 'memo_data', 'categories'));
    }

    /**
     * この関数はメモの保存処理を担当しています。
     * @param  \App\Http\Requests\StoreMemoRequest  $request
     * $requestには、
     * メモのmemo_data
     * カテゴリーのcategories
     * メモのtitle
     * が含まれています。
     * @return Illuminate\View\View
     * メモの詳細画面を返します。
     * TODO 後でStoreMemoRequestに型を書き換えて、フォームバリデーションを
     * 実装
     */
    public function store(StoreMemoRequest $request)
    {
        $user_id = Auth::id();
        $categories = $request->input('categories');
        $title = $request->input('title');
        $memo_data = $request->input('memo_data');
        DB::beginTransaction();

        try {
            $memo_id = Memo::store($user_id, $title, $memo_data);
            $insert_categories = $this->memo->insertCategories($categories, $memo_id);
            DB::commit();
            return view('EngineerStack.detailed_memo', compact('memo_data',
                                            'title', 'categories', 'memo_id'));
        } catch (Exception $exception) {
            DB::rollback();
            return redirect()->route('dashboard');
        }
        
    }

    /**
     * この関数はメモの編集フォームの返却を担当しています。
     * @param int $id
     * メモの主キーです。
     * @return @return Illuminate\View\View
     * メモの編集画面を返します。
     * TODO: 後ほど、user_idが異なるアカウントでredirectが
     * 発動するかテスト
     */
    public function edit(int $id)
    {
        $memo_id = $id;
        $is_owner = $this->memo->checkOwner($memo_id);
        
        if(!$is_owner) {
            return redirect()->route('dashboard');
        }

        $memo = Memo::find($memo_id);
        $categories = Memo::find($memo_id)->categories->pluck('name');
        $memo_data = $memo['memo_data'];
        return view('EngineerStack.edit_memo'
                , compact('memo', 'memo_data', 'categories'));
    }

    /**
     * この関数はメモデータの更新処理を担当しています。
     * @param Illuminate\Http\Request $request
     * $requestには、
     * メモのid
     * メモのmemo_data
     * メモのtitle
     * が含まれています。
     * @return Illuminate\View\View
     * メモ詳細画面を返す。
     */
    public function update(StoreMemoRequest $request)
    {
        $memo_id = $request->input('memo_id');
        $memo_data = $request->input('memo_data');
        $title = $request->input('title');
        $categories = $request->input('categories');
        $check_owner = $this->memo->checkOwner($memo_id);
        DB::beginTransaction();

        try {
            Memo::where('id', $memo_id)
                    ->update(['memo_data' => $memo_data, 'title' => $title]);
            $title = Memo::find($memo_id)->title;
            $this->memo->categoriesSync($memo_id, $categories);
            $memo_data = Memo::find($memo_id)->memo_data;
            DB::commit();
            return view('EngineerStack.detailed_memo'
                    , compact('title', 'categories', 'memo_data', 'memo_id'));
        } catch (Exception $exception) {
            DB::rollback();
            return redirect()->route('dashboard');
        }
    }

    /** 
     * この関数はメモ一件の詳細画面を作成を担当しています。
     * @param Illuminate\Http\Request $request
     * $requestには
     * メモのid
     * メモのmemo_data 
     * が含まれています。
     * @return Illuminate\View\View
     * メモ詳細画面を返す。
     */
    public function show(Request $request)
    {
        $memo_id = $request->input('memo_id');
        $memo_data = $request->input('memo_data');
        $this->memo->checkOwner($memo_id);
        $title = Memo::find($memo_id)->title;
        $categories = Memo::find($memo_id)->categories->pluck('name');
        $memo_data = Memo::find($memo_id)->memo_data;
        return view('EngineerStack.detailed_memo',
                compact('title', 'categories', 'memo_data', 'memo_id'));
    }

    public function searchTitle(Request $request)
    {
        $user_id = Auth::id();
        $search_word = $request->input('search_word');
        $memos = Memo::where('title', 'LIKE', "%$search_word%")
            ->where('memo_data', 'LIKE', "%$search_word%")
            ->where('user_id', $user_id)->get();
        $memo_data = $memos->pluck('memo_data');
        $categories = $this->memo->getCategories($memos);
        return view('EngineerStack.search_result', 
                compact('search_word', 'memos', 'memo_data', 'categories'));
    }

    public function searchCategory(Request $request)
    {
        $user_id = Auth::id();
        $category = $request->input('category');
        $search_word = $category;
        $posted_memo = Memo::where('user_id', $user_id)->get();
        $memos = $this->memo->getMemos($category);
        $memo_data = $memos->pluck('memo_data');
        $categories = $this->memo->getCategories($posted_memo);
        return view('EngineerStack.search_result', 
                compact('search_word', 'memos', 'memo_data', 'categories'));
    }

    /**
     * この関数はメモ一件を削除する処理を担当しています。
     * @param Illuminate\Http\Request $request
     * $requestには、
     * メモのid
     * が含まれています。
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     * メモ削除完了後画面を返します。
     */
    public function destroy(Request $request)
    {
        $memo_id = $request->input('memo_id');
        $this->memo->checkOwner($memo_id);
        Memo::destroy($memo_id);
        return redirect()->route('memos.deleted');
    }
}
