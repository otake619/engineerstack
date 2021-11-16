<?php
    namespace App\Services;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use App\Models\Memo;
    use App\Models\Category;
    use App\Models\CategoryMemo;

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
            $is_exist = Memo::where('id', $memo_id)->first();

            if(is_null($is_exist)) {
                return false;
            }

            $memo_owner = Memo::find($memo_id)->user_id;
            
            if($memo_owner != Auth::id()) {
                return false;
            }

            return true;
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

        /**
         * memoのidと一致するcategoryレコードのnameを取得して返す。
         * @param object $memos 
         * memoのコレクション
         * @return object $categories
         * categoryコレクションのnameカラムだけ抜き出したコレクション。
         */
        public function getCategories(object $memos)
        {
            $get_categories = app()->make('App\Http\Controllers\CategoryController');
            $categories = $get_categories->getCategories($memos);
            return $categories;
        }

        public function getMemos(string $category)
        {
            $memos = collect();
            $category_id = Category::where('name', $category)->pluck('id');
            $memo_ids = CategoryMemo::where('category_id', $category_id)->pluck('memo_id');
            $memo_count = count($memo_ids);
            for($index = 0; $index < $memo_count; $index++) {
                $memo = Memo::where('id', $memo_ids[$index])->get();
                $memos = $memos->concat($memo);
            }
            return $memos;
        }

        /**
         * この関数はキーワードでのメモ検索を担当しています。
         * @param Illuminate\Http\Request $request
         * $requestには、キーワード、現在のページ番号が入っています。
         * @return Illuminate\View\View
         * メモ検索結果を返します。
         */
        public function search(Request $request)
        {
            $user_id = Auth::id();
            $memos = Memo::where('user_id', $user_id)->get();
            $categories = $this->getCategories($memos);
            $search_word = $request->input('search_word');
            $search_title = Memo::where('title', 'LIKE', "%$search_word%")
                ->where('user_id', $user_id)->get();
            $search_memo = Memo::where("memo_data->blocks", 'LIKE', "%$search_word%")->pluck('memo_data');
            $memo_count = count($search_memo);
            $search_memo = json_decode($search_memo, true);
            $hit_id = [];
            for($index = 0; $index < $memo_count; $index++) {
                $memo = $search_memo[$index];
                $json_memo = json_decode($memo, true);
                $block_count = count($json_memo['blocks']);
                for($block = 0; $block < $block_count; $block++) {
                    if(strpos($json_memo['blocks'][$block]['data']['text'], $search_word) !== false) {
                        $id = $json_memo['blocks'][$block]['id'];
                        array_push($hit_id, $id);
                    }
                }
            }
            
            $hit_memos = collect();
            $length = count($hit_id);
            for($index = 0; $index < $length; $index++) {
                $search_json = Memo::where("memo_data->blocks", 'LIKE', "%$hit_id[$index]%")->get();
                $hit_memos = $hit_memos->concat($search_json);
            }

            $hit_memos = $hit_memos->concat($search_title);
            $hit_memos = $hit_memos->unique('memo_data');
            $current_page = $request->input('page');
            if(empty($current_page)) {
                $current_page = 1;
            }
            $memos = $hit_memos->forPage($current_page, 6);
            $total_pages = (int)ceil(count($hit_memos)/6);
            return view('EngineerStack.search_result', 
                    compact('search_word', 'memos', 'categories', 'current_page', 'total_pages'));
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
            $user_id = Auth::id();
            $category = $request->input('search_word');
            $search_word = $category;
            $posted_memo = Memo::where('user_id', $user_id)->get();
            $hit_memos = $this->getMemos($category);
            $categories = $this->getCategories($posted_memo);
            $current_page = $request->input('page');
            if(empty($current_page)) {
                $current_page = 1;
            }
            $memos = $hit_memos->forPage($current_page, 6);
            $total_pages = (int)ceil(count($hit_memos)/6);
            return view('EngineerStack.search_result', 
                    compact('search_word', 'memos','categories', 'current_page', 'total_pages'));
        }
    }