<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;


class CategoryFactory extends Factory
{
    /**
     * モデルの名前。
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * $modelの状態を記述。
     *
     * @return array
     */
    public function definition()
    {
        $category_name = $this->faker->unique()->word()
                             . $this->faker->unixTime;
        return [
            'name' => $category_name,
        ];
    }
}
