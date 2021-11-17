<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Memo;
use App\Models\Category;

class CategoryMemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // for ($i = 0; $i < 30; $i++){
        //     $set_memo_id = Memo::select('id')->orderByRaw("RAND()")->first()->id;
        //     $set_category_id = Category::select('id')->orderByRaw("RAND()")->first()->id;

        //     $cateogory_memo = DB::table('category_memos')
        //                     ->where([
        //                         ['memo_id', '=', $set_memo_id],
        //                         ['category_id', '=', $set_category_id]
        //                     ])->get();

        //     if($cateogory_memo->isEmpty()){
        //         DB::table('category_memos')->insert(
        //             [
        //                 'memo_id' => $set_memo_id,
        //                 'category_id' => $set_category_id,
        //             ]
        //         );
        //     }else{
        //         $i--;
        //     }
        // }
    }
}
