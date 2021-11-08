<?php
    namespace App\Services;

    use App\Models\Category;

    class CategoryService {
        /**
         * メモ記録画面で入力されたカテゴリー文字列を配列に変換してMemoController
         * へ返す。
         * @param string: $categories
         * @return array: $categories_arr
         */
        public function strToArr($categories)
        {
            $separater = ",";
            $categories_arr = explode($separater, $categories);
            $categories_arr = array_filter($categories_arr, "strlen");
            $categories_arr = array_map("trim", $categories_arr);
            return $categories_arr;
        }

        /**
         * メモ記録画面で入力されたカテゴリで重複があった場合は無視し、
         * カテゴリがなかった場合は新規にカテゴリtableへinsertする。
         * また、カテゴリtableとメモtableを紐づけるCategoryMemosへ
         * 外部キーをinsertする。
         * @param string $categories
         * メモ記録画面で入力されたカテゴリ。
         * @param int $memo_id
         * メモのinsert時に取得したメモのid。
         * @return void
         */
        public function insertCategoryMemos($categories, $memo_id)
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
        }
    }