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
            //自動増分値。
            $table->id();
            //usersテーブルのid。
            $table->integer('user_id');
            //メモのタイトル。
            $table->string('title');
            //editor.jsでのjson形式でのメモデータ。
            $table->json('memo_data');
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
        Schema::dropIfExists('memos');
    }
}
