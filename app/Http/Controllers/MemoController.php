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
        $user_id = Auth::id();
        $categories = $request->input('categories');
        $memo = $request->input('memo');

        DB::beginTransaction();

        try {
            $message = "メモの投稿が完了しました。";
            $memo_id = Memo::store($user_id, $memo);
            $memo = Memo::find($memo_id);
            $insert_categories = $this->memo->insertCategories($categories, $memo_id);
            $request->session()->regenerateToken();
            if($insert_categories > 5) {
                DB::rollback();
                return redirect()->route('memos.get.input')->with('message', 'カテゴリの最大数は5つです。');
            }
            DB::commit();
            return view('EngineerStack.detailed_memo', compact('memo', 'categories', 'message'));
        } catch (Exception $exception) {
            DB::rollback();
            return redirect()->route('memos.get.input')->with('message', 'メモの投稿に失敗しました。');
        }
    }

    /**
     * この関数はメモの編集フォームの返却を担当しています。
     * @param int $id
     * メモの主キーです。
     * @return @return Illuminate\View\View
     * メモの編集画面を返します。
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
     * メモデータ
     * が含まれています。
     * @return Illuminate\View\View
     * メモ詳細画面を返す。
     */
    public function update(StoreMemoRequest $request)
    {
        $memo = $request->input('memo');
        $memo_id = $request->input('memo_id');
        $categories = $request->input('categories');
        $is_owner = $this->memo->checkOwner($memo_id);
        
        if(!$is_owner) {
            return redirect()->route('dashboard')->with('alert', 'メモの更新に失敗しました。');
        }

        DB::beginTransaction();

        try {
            $message = 'メモの更新が完了しました。';
            Memo::where('id', $memo_id)
                    ->update(['memo' => $memo]);
            $categories_count = $this->memo->categoriesSync($memo_id, $categories);
            $request->session()->regenerateToken();

            if($categories_count > 5) {
                DB::rollback();
                return redirect()->route('dashboard')->with('alert', 'メモの更新に失敗しました。');
            }

            $memo = Memo::find($memo_id);
            DB::commit();
            return view('EngineerStack.detailed_memo'
                    , compact('categories', 'memo', 'message'));
        } catch (Exception $exception) {
            DB::rollback();
            return redirect()->route('dashboard')->with('alert', 'メモの更新に失敗しました。');
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
        $is_owner = $this->memo->checkOwner($memo_id);
        
        if(!$is_owner) {
            return redirect()->route('dashboard');
        }

        $memo = Memo::find($memo_id);
        $categories = Memo::find($memo_id)->categories->pluck('name');
        return view('EngineerStack.detailed_memo',
                compact('memo', 'categories'));
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
        $search_word = $request->input('search_word');
        if(mb_strlen($search_word) > 40) {
            return redirect()->route('dashboard')->with('message', '検索語句が長すぎます。');
        } elseif($search_word === null) {
            return redirect()->route('dashboard')->with('message', '検索語句は必須です。');
        }
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
        $search_word = $request->input('search_word');
        if(mb_strlen($search_word) > 40) {
            return redirect()->route('dashboard')->with('message', '検索語句が長すぎます。');
        } elseif($search_word === null) {
            return redirect()->route('dashboard')->with('message', '検索語句は必須です。');
        }
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

    /**
     * 
     * 
     */
    public function allCategories()
    {
        $user_id = Auth::id();
        $posted_memos = Memo::where('user_id', $user_id)->get();
        $categories = $this->memo->getCategories($posted_memos);
        return view('EngineerStack.all_categories', compact('categories'));
    }
}
