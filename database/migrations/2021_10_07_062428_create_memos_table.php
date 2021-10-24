<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memos', function (Blueprint $table) {
            $table->id()->comment('自動増分値');
            $table->integer('user_id')->comment('usersテーブルのid');
            $table->string('title')->comment('メモのタイトル');
            $table->json('memo_data')->comment('editor.jsで作成したjsonメモデータ');
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
        Schema::dropIfExists('memos');
    }
}
