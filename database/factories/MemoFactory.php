<?php

namespace Database\Factories;

use App\Models\Memo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

class MemoFactory extends Factory
{
    /**
     * モデルの名前。
     *
     * @var string
     */
    protected $model = Memo::class;

    /**
     * $modelの状態を記述。
     *
     * @return array
     */
    public function definition()
    {
        $text = $this->faker->realText(50);
        $memo_data = "{
                        \"time\": \"{$this->faker->unixTime}\",
                        \"blocks\": [
                            {
                                \"id\": \"0{$this->faker->unixTime}\",
                                \"data\": {
                                    \"text\": \"{$text}\"
                                },
                                \"type\": \"paragraph\"
                            }
                        ],
                        \"version\": \"2.22.2\"
                    }";

        return [
            'user_id' => 1,
            'memo_text' => $text,
            'memo_data' => $memo_data,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
