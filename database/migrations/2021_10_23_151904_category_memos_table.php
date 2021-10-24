<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CategoryMemosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_memos', function (Blueprint $table) {
            $table->id()->comment('自動増分値');
            $table->integer('category_id')->comment('categoriesテーブルのid');
            $table->integer('memo_id')->comment('memosテーブルのid');
            $table->timestamps()->comment('タイムスタンプ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_memos');
    }
}
