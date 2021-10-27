<?php
    class CategoryService {
        /**
         * メモ記録画面で入力されたカテゴリー文字列を配列に変換してMemoController
         * へ返す。
         * @param 
         * string: $categories
         * @return 
         * array: $categories_arr
         */
        public function str_to_arr($categories)
        {
            $separater = ",";
            $categories_arr = explode($separater, $categories);
            $categories_arr = array_filter($categories_arr, "strlen");
            $categories_arr = array_map("trim", $categories_arr);
            return $categories_arr;
        }
    }