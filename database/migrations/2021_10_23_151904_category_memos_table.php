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
            //自動増分値。
            $table->id();
            //categoryテーブルのid。
            $table->integer('category_id');
            //memosテーブルのid。
            $table->integer('memo_id');
            //タイムスタンプ。created_atとupdated_at。
            $table->timestamps();
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
