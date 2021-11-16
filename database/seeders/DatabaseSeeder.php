<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Memo;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Memo::factory(10)->create();
        $memos = Memo::all();
    }
}
