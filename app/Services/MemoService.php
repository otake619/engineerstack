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
            return $insert_categories->store($categories, $memo_id);
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
            
            return $arr_length;
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
            $memos = $memos->sortByDesc('id');
            $categories = $get_categories->getCategories($memos);
            return $categories;
        }

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
        public function searchKeyword(Request $request)
        {
            $is_search_category = false;
            $memos = collect();
            $user_id = Auth::id();
            $search_word = $request->input('search_word');
            $current_page = $request->input('page');
            $sort = $request->input('sort');
            $all_memos = Memo::where('user_id', $user_id)->get();
            $categories = $this->getCategories($all_memos)->slice(0, 15);

            if($sort == "ascend") {
                $memo_collection = Memo::where('user_id', $user_id)
                        ->orderBy('updated_at', 'desc')
                        ->where('memo_text', 'LIKE', "%$search_word%")->get();
            } elseif($sort == "descend") {
                $memo_collection = Memo::where('user_id', $user_id)
                        ->orderBy('updated_at', 'asc')
                        ->where('memo_text', 'LIKE', "%$search_word%")->get();
            } else {
                $memo_collection = Memo::where('user_id', $user_id)
                        ->orderBy('updated_at', 'desc')
                        ->where('memo_text', 'LIKE', "%$search_word%")->get();
            }

            if(empty($current_page)) {
                $current_page = 1;
            }

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
        public function searchCategory(Request $request)
        {
            $is_search_category = true;
            $memos = collect();
            $user_id = Auth::id();
            $category = $request->input('search_word');
            $sort = $request->input('sort');
            $current_page = $request->input('page');
            $search_word = $category;
            $all_memos = Memo::where('user_id', $user_id)->get();
            $categories = $this->getCategories($all_memos)->slice(0, 15);
            $memo_collection = $this->getMemos($category, $sort);

            if(empty($current_page)) {
                $current_page = 1;
            }

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

        /**
         * editor.jsで作成されたjsonデータを受け取り、
         * text部分を抽出してtextを返す関数。
         * @param string $memo_data
         * メモ入力画面にて作成されたメモデータ。
         * @return string $memo_text
         * メモ入力画面にて作成されたメモデータの
         * テキスト部分。
         */
        public function getMemoText(string $memo_data)
        {
            $memo_data = json_decode($memo_data, true);
            $memo_text = "";
            $block_length = count($memo_data['blocks']);
            $block = $memo_data['blocks'];
    
            for($block_index = 0; $block_index < $block_length; $block_index++) {
                $type = $block[$block_index]['type'];

                switch($type) {
                    case "paragraph": 
                        $memo_text .= $this->getParagraphText($block[$block_index]);
                        break;
                    case "code": 
                        $memo_text .= $this->getCodeText($block[$block_index]);
                        break;
                    case "quote": 
                        $memo_text .= $this->getQuoteText($block[$block_index]);
                        break;
                    case "header":
                        $memo_text .= $this->getHeaderText($block[$block_index]);
                        break;
                    case "list":
                        $memo_text .= $this->getListText($block[$block_index]);
                        break;
                    default: 
                        return;
                }
            }

            return $memo_text;
        }

        public function getParagraphText(array $data) {
            $text = "";
            $text .= $data['data']['text'];
            return $text . "\n";
        }

        public function getCodeText(array $data) {
            $text = "";
            $text .= $data['data']['code'];
            return $text . "\n";
        }

        public function getQuoteText(array $data) {
            $text = "";
            $text .= $data['data']['text'];
            $text .= $data['data']['caption'];
            return $text . "\n";
        }

        public function getHeaderText(array $data) {
            $text = "";
            $text .= $data['data']['text'];
            return $text . "\n";
        }

        public function getListText(array $data) {
            $text = "";
            $item_length = count($data['data']['items']);

            for($index = 0; $index < $item_length; $index++) {
                $text .= $data['data']['items'][$index];
            }

            return $text . "\n";
        }
    }