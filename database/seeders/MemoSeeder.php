<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Memo;
use App\Models\CategoryMemo;

class MemoSeeder extends Seeder
{
    /**
     * ダミーデータを作成する関数。
     *
     * @return void
     */
    public function run()
    {
        $memo = Memo::factory()
                ->has(Category::factory()->count(5))->create();
    }
}
