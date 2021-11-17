<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Memo;
use App\Models\Category;
use App\Models\CategoryMemo;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryMemoSeeder extends Seeder
{
    /**
     * ダミーデータを作成する関数。
     *
     * @return void
     */
    public function run()
    {
        CategoryMemo::factory()->count(5)->create();
    }
}
