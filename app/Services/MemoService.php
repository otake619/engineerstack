<?php
    namespace App\Services;

    use Illuminate\Support\Facades\Auth;
    use App\Models\Memo;
    use App\Models\Category;

    class MemoService {
        /**
         * テーブルにカテゴリーを挿入してカテゴリーの要素数を取得
         * @param string $categories カテゴリ文字列
         * @param int $memo_id メモID
         * @return int $insert_categories カテゴリの要素数(通常)
         * @return int -1 カテゴリーの要素数が異常(異常)
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
         * カテゴリーの文字数が20文字よりも多いか判定して
         * 20文字以下ならカテゴリーを返す
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
         * メモIDからアクセスが所有者によるものか判定
         * @param int $memo_id メモID
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
         * 中間テーブル(CategoryMemos)のレコードの更新を行う。
         * カテゴリーの要素数が6以上なら更新しない。
         * カテゴリーの要素数を返す。
         * @param int $memo_id　メモID
         * @param string $categories カテゴリーのname
         * @return int $arr_length カテゴリーの要素数
         * @return int -1 カテゴリーの要素数が6以上
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
         * 文字列を配列に変換。
         * @param string $str 文字列
         * @return array $arr 配列
         */
        public function strToArr(string $str)
        {
            $separater = ",";
            $arr = explode($separater, $str);
            $arr = array_filter($arr, "strlen");
            $arr = array_map("trim", $arr);
            return $arr;
        }

        /**
         * メモに紐づくカテゴリを取得。
         * @param object $memos メモのcollection
         * @return object $categories カテゴリーのcollection
         */
        public function getCategories(object $memos)
        {
            $get_categories = app()->make('App\Http\Controllers\CategoryController');
            $memos = $memos->sortByDesc('id');
            $categories = $get_categories->getCategories($memos);
            return $categories;
        }

        /**
         * 検索語句と部分一致するカテゴリーに紐づくメモの取得。
         * @param string $search_word 検索語句
         * @param string $sort ソート方法
         * @return Illuminate\Support\Collection $memos メモのcollection
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
         * 検索語句に部分一致するメモを取得。
         * @param Illuminate\Http\Request $request 検索語句, 現在のページ番号,ソート方法
         * @return Illuminate\View\View メモ検索結果
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
         * 検索語句に部分一致するカテゴリーを取得して、
         * カテゴリーに紐づくメモを取得。
         * @param string $search_word 検索語句
         * @param ?int $current_page 現在のページ番号
         * @param ?string $sort ソート方法
         * @return Illuminate\View\View メモ検索結果
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