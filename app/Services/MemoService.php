<?php
    namespace App\Services;

    use App\Models\Memo;
    use App\Models\Category;
    use App\Models\CategoryMemos;

    class MemoService {
        /**
         * categoriesテーブルにカテゴリを挿入
         * 
         * @param string $categories
         * メモ記録画面で入力されたカテゴリ文字列
         * @param int $memo_id
         * memoレコードのid。
         * @return void
         */
        public function insertCategories(string $categories, int $memo_id)
        {
            $insert_categories = app()->make('App\Http\Controllers\CategoryController');
            $insert_categories->store($categories, $memo_id);
        }

        /**
         * この関数はメモに対して何らの処理を加える際に、
         * メモの所有者以外のアカウントがメモに処理を加え
         * ることを防ぐ処理を担当しています。
         * 
         * @param int $memo_id
         * メモの主キーです。
         * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
         * ホーム画面へリダイレクト。
         */
        public function checkOwner(int $memo_id)
        {
            $memo_owner = Memo::find($memo_id)->user_id;
            
            if($memo_owner != Auth::id()) {
                return redirect()->route('dashboard');
            }
        }
    }