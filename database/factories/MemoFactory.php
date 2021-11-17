<?php

namespace Database\Factories;

use App\Models\Memo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MemoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Memo::class;

    public function definition()
    {
        $memo_data = "{
                        \"time\": \"{$this->faker->unixTime}\",
                        \"blocks\": [
                            {
                                \"id\": \"0{$this->faker->unixTime}\",
                                \"data\": {
                                    \"text\": \"{$this->faker->realText(50)}\"
                                },
                                \"type\": \"paragraph\"
                            }
                        ],
                        \"version\": \"2.22.2\"
                    }";
                
        return [
            'user_id' => 1,
            'title' => $this->faker->sentence,
            'memo_data' => $memo_data,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
