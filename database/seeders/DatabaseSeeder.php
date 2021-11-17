<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Memo;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * ダミーデータを作成する関数。
     *
     * @return void
     */
    public function run()
    {
        Memo::factory(1)->create();
        Category::factory(5)->create();
    }
}
