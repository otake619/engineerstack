<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Memo;

class CategorySeeder extends Seeder
{
    /**
     * ダミーデータを作成する関数。
     *
     * @return void
     */
    public function run()
    {
        Category::factory()->count(5)->create();
    }
}
