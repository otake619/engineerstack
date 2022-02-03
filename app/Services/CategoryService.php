<?php
    namespace App\Services;

    use App\Models\Category;

    class CategoryService {
        /**
         * 文字列を配列に変換
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
         * 中間テーブルにメモとカテゴリーのidを挿入
         * @param string $categories カテゴリーの文字列
         * @param int $memo_id メモのid
         * @return int $arr_length カテゴリーの配列の要素数
         */
        public function insertCategoryMemos(string $categories, int $memo_id)
        {
            $category_arr = $this->strToArr($categories);
            $arr_length = count($category_arr);
            //TODO 後でupsertを使用するか検討
            for($index = 0; $index < $arr_length; $index++) {
                $is_exist = Category::where('name', $category_arr[$index])->exists();
                if($is_exist) {
                    $category_id = Category::where('name', $category_arr[$index])->first()->id;
                } else {
                    $category_id = Category::store($category_arr[$index]);
                }
                $insert_category_memos = app()->make('App\Http\Controllers\CategoryMemosController');
                $insert_category_memos->store($memo_id, $category_id);
            }
            return $arr_length;
        }

        /**
         * 引数のメモに対応したカテゴリーを取得
         * @param object $memos　メモのcollection
         * @return object $unique_categories カテゴリーのcollection
         */
        public function getCategories(object $memos) 
        {
            $category_collection = collect();
            
            foreach($memos as $memo) {
                $to_collection = collect($memo->categories);
                $category_collection = $category_collection->concat($to_collection);
            }
            $unique_categories = $category_collection->unique('name')->pluck('name');
            return $unique_categories;
        }
    }