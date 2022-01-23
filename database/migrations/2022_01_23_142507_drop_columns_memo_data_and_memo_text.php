<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnsMemoDataAndMemoText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memos', function (Blueprint $table) {
            $table->dropColumn(['memo_data', 'memo_text']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memos', function (Blueprint $table) {
            $table->json('memo_data')->nullable()->comment('editor.jsで作成したjsonメモデータ');
            $table->text('memo_text')->comment('メモデータのテキスト');
        });
    }
}
