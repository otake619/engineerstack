<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMemoRequest;
use App\Models\Memo;
use App\Models\Category;
use App\Services\MemoService;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Exception;

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
        $memos = Memo::where('user_id', $user_id)->orderBy('updated_at', 'desc')->paginate(6);
        $posted_memos = Memo::where('user_id', $user_id)->get();
        $categories = $this->memo->getCategories($posted_memos);
        $categories = $categories->slice(0, 15);
        return view('EngineerStack.home', compact('memos', 'categories'));
    }

    /**
     * この関数はメモの保存処理を担当しています。
     * @param  \App\Http\Requests\StoreMemoRequest  $request
     * $requestには、
     * メモのmemo_data
     * カテゴリーのcategories
     * が含まれています。
     * @return Illuminate\View\View
     * メモの詳細画面を返します。
     */
    public function store(StoreMemoRequest $request)
    {
        $validated = $request->validated();
        $user_id = Auth::id();
        $categories = $request->input('categories');
        $memo_data = $request->input('memo_data');
        $memo_text = $this->memo->getMemoText($memo_data);

        DB::beginTransaction();

        try {
            $memo_id = Memo::store($user_id, $memo_data, $memo_text);
            $insert_categories = $this->memo->insertCategories($categories, $memo_id);
            DB::commit();
            return view('EngineerStack.detailed_memo', compact('memo_data',
                                            'categories', 'memo_id'));
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
        return view('EngineerStack.edit_memo'
                , compact('memo', 'categories'));
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
        $categories = $request->input('categories');
        $is_owner = $this->memo->checkOwner($memo_id);
        
        if(!$is_owner) {
            return redirect()->route('dashboard');
        }
        DB::beginTransaction();

        try {
            $memo_text = $this->memo->getMemoText($memo_data);
            Memo::where('id', $memo_id)
                    ->update(['memo_data' => $memo_data, 'memo_text' => $memo_text]);
            $this->memo->categoriesSync($memo_id, $categories);
            $memo_data = Memo::find($memo_id)->memo_data;
            DB::commit();
            return view('EngineerStack.detailed_memo'
                    , compact('categories', 'memo_data', 'memo_id'));
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
        $is_owner = $this->memo->checkOwner($memo_id);
        
        if(!$is_owner) {
            return redirect()->route('dashboard');
        }
        $title = Memo::find($memo_id)->title;
        $categories = Memo::find($memo_id)->categories->pluck('name');
        $memo_data = Memo::find($memo_id)->memo_data;
        return view('EngineerStack.detailed_memo',
                compact('title', 'categories', 'memo_data', 'memo_id'));
    }

    /**
     * この関数はキーワードでのメモ検索を担当しています。
     * @param Illuminate\Http\Request $request
     * $requestには、キーワード、現在のページ番号が入っています。
     * @return Illuminate\View\View
     * メモ検索結果を返します。
     */
    public function searchKeyword(Request $request)
    {
        return $this->memo->searchKeyword($request);
    }

    /**
     * この関数はカテゴリでのメモ検索を担当しています。
     * @param Illuminate\Http\Request $request
     * $requestには、カテゴリ名が入っています。
     * @return Illuminate\View\View
     * メモ検索結果を返します。
     */
    public function searchCategory(Request $request)
    {
        return $this->memo->searchCategory($request);
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
        $is_owner = $this->memo->checkOwner($memo_id);
        
        if(!$is_owner) {
            return redirect()->route('dashboard');
        }
        Memo::destroy($memo_id);
        return redirect()->route('memos.deleted');
    }

    public function allCategories()
    {
        $user_id = Auth::id();
        $posted_memos = Memo::where('user_id', $user_id)->get();
        $categories = $this->memo->getCategories($posted_memos);
        return view('EngineerStack.all_categories', compact('categories'));
    }
}
