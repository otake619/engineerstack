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
            $search_word = $request->input('search_word');
            $current_page = $request->input('page');
            $all_memos = Memo::where('user_id', $user_id)->get();
            $categories = $this->getCategories($all_memos);

            $memos = Memo::where('user_id', $user_id)
                        ->where('memo_text', 'LIKE', "%$search_word%")->get();

            if(empty($current_page)) {
                $current_page = 1;
            }
            $memos = $memos->forPage($current_page, 6);
            $total_pages = (int)ceil(count($memos)/6);
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
            $all_memos = Memo::where('user_id', $user_id)->get();
            $hit_memos = $this->getMemos($category);
            $categories = $this->getCategories($all_memos);
            $current_page = $request->input('page');
            if(empty($current_page)) {
                $current_page = 1;
            }
            $memos = $hit_memos->forPage($current_page, 6);
            $total_pages = (int)ceil(count($hit_memos)/6);
            return view('EngineerStack.search_result', 
                    compact('search_word', 'memos','categories', 'current_page', 'total_pages'));
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