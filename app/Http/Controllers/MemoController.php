<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMemoRequest;
use App\Models\Memo;
use App\Services\MemoService;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Exception;

class MemoController extends Controller
{
    private $memo;

    /**
     * 認証前ではメモに関するデータにはアクセス不可
     * @param App\Services\MemoService $memoService メモのサービスクラス
     * @return void
     */
    public function __construct(MemoService $memoService)
    {
        $this->middleware('auth');
        $this->memo = $memoService;
    }

    /**
     * メモの全件取得
     * @param void
     * @return Illuminate\View\View ホーム画面
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
     * メモの保存
     * @param  \App\Http\Requests\StoreMemoRequest  $request memoとcategory
     * @return Illuminate\View\View メモの詳細画面
     */
    public function store(StoreMemoRequest $request)
    {
        $user_id = Auth::id();
        $categories = $request->input('categories');
        $memo = $request->input('memo');

        DB::beginTransaction();

        try {
            $memo_id = Memo::store($user_id, $memo);
            $insert_categories = $this->memo->insertCategories($categories, $memo_id);
            $request->session()->regenerateToken();
            if($insert_categories > 5) {
                DB::rollback();
                return redirect()->route('memos.get.input')->with('alert', 'カテゴリーの最大数は5つです。');
            } else if($insert_categories < 0) {
                return redirect()->route('memos.get.input')->with('alert', 'カテゴリーの最大文字数を超えています。');
            }
            DB::commit();
            $message = "メモの投稿が完了しました。";
            $memo = Memo::find($memo_id);
            return view('EngineerStack.detailed_memo', compact('memo', 'categories', 'message'));
        } catch (Exception $exception) {
            DB::rollback();
            return redirect()->route('memos.get.input')->with('alert', 'メモの投稿に失敗しました。');
        }
    }

    /**
     * メモの編集画面の表示
     * @param int $id メモの主キー
     * @return @return Illuminate\View\View メモの編集画面
     */
    public function edit(int $memo_id)
    {
        $is_owner = $this->memo->checkOwner($memo_id);
        if($is_owner === false) return redirect()->route('dashboard');
        $memo = Memo::find($memo_id);
        $categories = Memo::find($memo_id)->categories->pluck('name');
        return view('EngineerStack.edit_memo'
                , compact('memo', 'categories'));
    }

    /**
     * メモの更新
     * @param Illuminate\Http\Request $request メモのidとmemo
     * @return Illuminate\View\View メモ詳細画面
     */
    public function update(StoreMemoRequest $request)
    {
        $memo = $request->input('memo');
        $memo_id = $request->input('memo_id');
        $categories = $request->input('categories');
        $is_owner = $this->memo->checkOwner($memo_id);
        if($is_owner === false) return redirect()->route('dashboard')
                            ->with('alert', 'メモの更新に失敗しました。');

        DB::beginTransaction();

        try {
            Memo::where('id', $memo_id)
                    ->update(['memo' => $memo]);
            $categories_count = $this->memo->categoriesSync($memo_id, $categories);
            $request->session()->regenerateToken();

            if($categories_count > 5) {
                DB::rollback();
                return redirect()->route('dashboard')->with('alert', 'メモの更新に失敗しました。');
            } else if($categories_count < 0) {
                return redirect()->route('memos.get.input')->with('alert', 'カテゴリの最大文字数を超えています。');
            }

            DB::commit();
            $message = 'メモの更新が完了しました。';
            $memo = Memo::find($memo_id);
            return view('EngineerStack.detailed_memo'
                    , compact('categories', 'memo', 'message'));
        } catch (Exception $exception) {
            DB::rollback();
            return redirect()->route('dashboard')->with('alert', 'メモの更新に失敗しました。');
        }
    }

    /** 
     * メモ一件の詳細
     * @param Illuminate\Http\Request $request メモのidとmemo
     * @return Illuminate\View\View メモ詳細画面
     */
    public function show(Request $request)
    {
        $memo_id = $request->input('memo_id');
        $is_owner = $this->memo->checkOwner($memo_id);
        if($is_owner === false) return redirect()->route('dashboard')
                            ->with('alert', 'アクセス不可なIDです。');
        $memo = Memo::find($memo_id);
        $categories = Memo::find($memo_id)->categories->pluck('name');
        return view('EngineerStack.detailed_memo',
                compact('memo', 'categories'));
    }

    /**
     * キーワードでのメモ検索
     * @param Illuminate\Http\Request $request 現在のページ番号, キーワード
     * @return Illuminate\View\View メモの検索結果
     */
    public function searchKeyword(Request $request)
    {
        $search_word = $request->input('search_word');
        $current_page = $request->input('page');
        $sort = $request->input('sort');

        if($current_page === null) $current_page = 1;
        if(mb_strlen($search_word) > 40) {
            return redirect()->route('dashboard')->with('message', '検索語句が長すぎます。');
        } elseif($search_word === null) {
            return redirect()->route('dashboard')->with('message', '検索語句は必須です。');
        }
        return $this->memo->searchKeyword($search_word, $current_page, $sort);
    }

    /**
     * カテゴリでのメモ検索
     * @param Illuminate\Http\Request $request category
     * @return Illuminate\View\View メモ検索結果
     */
    public function searchCategory(Request $request)
    {
        $search_word = $request->input('search_word');
        $current_page = $request->input('page');
        $sort = $request->input('sort');

        if(mb_strlen($search_word) > 40) {
            return redirect()->route('dashboard')->with('alert', '検索語句が長すぎます。');
        } elseif($search_word === null) {
            return redirect()->route('dashboard')->with('alert', '検索語句は必須です。');
        }
        return $this->memo->searchCategory($search_word, $current_page, $sort);
    }

    /**
     * メモの削除
     * @param Illuminate\Http\Request $request　メモのid
     * @return Illuminate\Http\RedirectResponse メモ削除完了後画面
     */
    public function destroy(Request $request)
    {
        $memo_id = $request->input('memo_id');
        $is_owner = $this->memo->checkOwner($memo_id);
        if($is_owner == false) return redirect()->route('dashboard')
                            ->with('alert', 'アクセス不可なIDです。');
        Memo::destroy($memo_id);
        return redirect()->route('memos.deleted');
    }

    /**
     * カテゴリーの全件取得
     * @return Illuminate\View\View カテゴリー一覧 
     */
    public function allCategories()
    {
        $user_id = Auth::id();
        $posted_memos = Memo::where('user_id', $user_id)->get();
        $categories = $this->memo->getCategories($posted_memos);
        return view('EngineerStack.all_categories', compact('categories'));
    }
}
