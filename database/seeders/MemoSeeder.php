<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Memo;

class MemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Memo::factory()
            ->hasAttached(
                Category::factory()->count(1),
                ['name' => $this->faker->word]
            )
        ->create();
    }
}
