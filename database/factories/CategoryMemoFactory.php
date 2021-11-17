<?php

namespace Database\Factories;

use App\Models\CategoryMemo;
use App\Models\Category;
use App\Models\Memo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

class CategoryMemoFactory extends Factory
{
    /**
     * モデルの名前。
     *
     * @var string
     */
    protected $model = CategoryMemo::class;

    /**
     * $modelの状態を記述。
     *
     * @return array
     */
    public function definition()
    {
        $random_category_id = Category::select('id')->orderByRaw("RAND()")
        ->first()->id;

        CategoryMemo::factory()->create([
            'category_id' => $random_category_id,
            'memo_id' => Memo::factory()->create()->id
        ]);
    }
}
