<?php
    namespace App\Services;

    use Illuminate\Support\Facades\Auth;
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

        /**
         * カテゴリとメモの中間テーブルの更新処理。
         * @param int $memo_id
         * memoレコードのid。
         * @param string $categories
         * メモ編集画面で入力されたカテゴリの文字列。
         */
        public function categoriesSync(int $memo_id, string $categories)
        {
            $categories_arr = $this->strToArr($categories);
            $arr_length = count($categories_arr);
            $category_ids = [];
            for($index = 0; $index < $arr_length; $index++) {
                $is_exist = Category::where('name', $categories_arr[$index])->exists();
                if($is_exist) {
                    $category_id = Category::where('name', $categories_arr[$index])->first()->id;
                } else {
                    $category_id = Category::store($categories_arr[$index]);
                }
                array_push($category_ids, $category_id);
            }
            $memo = Memo::find($memo_id);
            $memo->categories()->sync($category_ids);
        }

        /**
         * カテゴリー文字列を配列に変換して返す。
         * @param string: $categories
         * @return array: $categories_arr
         */
        public function strToArr(string $categories)
        {
            $separater = ",";
            $categories_arr = explode($separater, $categories);
            $categories_arr = array_filter($categories_arr, "strlen");
            $categories_arr = array_map("trim", $categories_arr);
            return $categories_arr;
        }
    }