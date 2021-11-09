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
        'memo_data'
    ];

    /**
     * メモ記録画面で入力されたメモをDBへ格納する関数。
     * @param int $user_id
     * メモデータを記録したuserのid。
     * @param string $title
     * メモデータのタイトル。
     * @param string $memo_data
     * editor.jsで作成されたメモデータ。
     * @return int $memo->id 
     * DBに格納されたmemoレコードのid。
     */
    public static function store(int $user_id, string $title, string $memo_data)
    {
        $memo = Memo::create([
            'user_id' => $user_id,
            'title' => $title,
            'memo_data' => $memo_data
        ]);

        return $memo->id;
    }

    /**
     * Memoレコードに紐づくCategoryレコードを返す関数。
     * @return mixed Illuminate\Database\Eloquent\Relations\BelongsToMany
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
