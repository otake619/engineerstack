<?php
    namespace App\Services;

    use Illuminate\Support\Facades\Auth;
    use App\Models\Memo;
    use App\Models\Category;

    class MemoService {
        /**
         * categoriesテーブルにカテゴリを挿入
         * @param string $categories カテゴリ文字列
         * @param int $memo_id メモのid
         * @return int $insert_categories カテゴリの配列数
         */
        public function insertCategories(string $categories, int $memo_id)
        {
            $categories_arr = $this->strToArr($categories);
            $category_check = $this->arrValidation($categories_arr);
            if($category_check === false) return -1;
            $insert_categories = app()->make('App\Http\Controllers\CategoryController');
            return $insert_categories->store($categories, $memo_id);
        }

        /**
         * カテゴリーの文字数が20文字よりも多いか判定
         * @param array $categories カテゴリ
         * @return Boolean false 20文字よりも多い場合
         * @return array $categories 20文字以下の場合
         */
        function arrValidation(array $categories)
        {
            foreach($categories as $category) {
                $count_category = mb_strlen($category);
                if($count_category > 20) {
                    return false;
                }
            }

            return $categories;
        }

        /**
         * メモにアクセスする際に所有者か判定
         * @param int $memo_id メモのid
         * @return Boolean true 所有者だった場合
         * @return Boolean false 所有者ではなかった場合
         */
        public function checkOwner(int $memo_id)
        {   
            $is_exist = Memo::where('id', $memo_id)->first();
            if(is_null($is_exist))  return false;
            $memo_owner = Memo::find($memo_id)->user_id;
            if($memo_owner != Auth::id())  return false;
            return true;
        }

        /**
         * 中間テーブル(CategoryMemos)の更新
         * @param int $memo_id　メモのid。
         * @param string $categories カテゴリ
         * @return int $arr_length カテゴリ配列の要素数(カテゴリが20文字以内)
         * @return int -1 カテゴリが20文字より多かった場合
         */
        public function categoriesSync(int $memo_id, string $categories)
        {
            $categories_arr = $this->strToArr($categories);
            $category_check = $this->arrValidation($categories_arr);
            if($category_check === false) return -1;
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
            
            return $arr_length;
        }

        /**
         * カテゴリー文字列を配列に変換。
         * @param string $categories
         * @return array $categories_arr
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
         * メモに紐づくカテゴリを取得
         * @param object $memos メモのcollection
         * @return object $categories カテゴリーのname
         */
        public function getCategories(object $memos)
        {
            $get_categories = app()->make('App\Http\Controllers\CategoryController');
            $memos = $memos->sortByDesc('id');
            $categories = $get_categories->getCategories($memos);
            return $categories;
        }

        /**
         * 
         * 
         */
        public function getMemos(string $search_word, ?string $sort)
        {                       
            $memos = collect();
            $user_id = Auth::id();
            $categories = Category::where('name', 'LIKE', "%$search_word%")->get();

            $loop_length = count($categories);
            for($index = 0; $index < $loop_length; $index++) {
                $memo = $categories[$index]->memos;
                $memos = $memos->concat($memo);
            }

            $memos = $memos->where('user_id', $user_id);

            if($sort === "ascend") {
                $memos = $memos->sortByDesc('created_at');
            } else if($sort === "descend") {
                $memos = $memos->sortBy('created_at');
            } else {
                $memos = $memos->sortByDesc('created_at');
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
        public function searchKeyword(string $search_word, int $current_page, string $sort)
        {
            $is_search_category = false;
            $memos = collect();
            $user_id = Auth::id();
            $all_memos = Memo::where('user_id', $user_id)->get();
            $categories = $this->getCategories($all_memos)->slice(0, 15);

            if($sort === 'ascend') {
                $memo_collection = Memo::where('user_id', $user_id)
                        ->orderBy('updated_at', 'desc')
                        ->where('memo', 'LIKE', "%$search_word%")->get();
            } elseif($sort === "descend") {
                $memo_collection = Memo::where('user_id', $user_id)
                        ->orderBy('updated_at', 'asc')
                        ->where('memo', 'LIKE', "%$search_word%")->get();
            } else {
                $memo_collection = Memo::where('user_id', $user_id)
                        ->orderBy('updated_at', 'desc')
                        ->where('memo', 'LIKE', "%$search_word%")->get();
            }

            if(empty($current_page)) $current_page = 1;

            if($memo_collection->isEmpty()) {
                $total_pages = 0;
            } else {
                $total_pages = (int)ceil(count($memo_collection)/6);
                $memos = $memo_collection->forPage($current_page, 6);
            }

            return view('EngineerStack.search_result', 
                    compact('search_word', 'memos', 'categories',
                     'current_page', 'total_pages', 'sort', 'is_search_category'));
        }

        /**
         * この関数はカテゴリでのメモ検索を担当しています。
         * @param Illuminate\Http\Request $request
         * $requestには、カテゴリ名が入っています。
         * @return Illuminate\View\View
         * メモ検索結果を返します。
         */
        public function searchCategory(string $search_word, ?int $current_page, ?string $sort)
        {
            $is_search_category = true;
            $memos = collect();
            $user_id = Auth::id();
            $category = $search_word;
            $all_memos = Memo::where('user_id', $user_id)->get();
            $categories = $this->getCategories($all_memos)->slice(0, 15);
            $memo_collection = $this->getMemos($category, $sort);

            if(empty($current_page)) $current_page = 1;

            if($memo_collection->isEmpty()) {
                $total_pages = 0;
            } else {
                $total_pages = (int)ceil(count($memo_collection)/6);
                $memos = $memo_collection->unique('id')->forPage($current_page, 6);
            }

            return view('EngineerStack.search_result', 
                    compact('search_word', 'memos','categories',
                     'current_page', 'total_pages', 'sort', 'is_search_category'));
        }
    }