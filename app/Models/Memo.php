<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Memo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'user_id',
        'title',
        'memo',
    ];

    /**
     * メモの保存
     * @param int $user_id ユーザーID
     * @param string $memo メモのテキスト
     * @return int $memo->id メモのID
     */
    public static function store(int $user_id, string $memo)
    {
        $memo = Memo::create([
            'user_id' => $user_id,
            'memo' => $memo,
        ]);

        return $memo->id;
    }

    /**
     * 中間テーブルを使用してメモに対応するカテゴリーを取得
     * @return mixed Illuminate\Database\Eloquent\Relations\BelongsToMany カテゴリー
     */
    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            CategoryMemo::class,
            'memo_id',
            'category_id'
        );
    }
}
