<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'name',
    ];

    /**
     * メモ記録画面で入力されたカテゴリをDBへ格納する関数。
     * @param string $name
     * メモ入力画面で入力されたカテゴリ名。
     * @return int $category->id
     * DBにinsertされたcategoryレコードのid。
     */
    public static function store(string $name)
    {
        $category = Category::create([
            'name' => $name,
        ]);

        return $category->id;
    }

    /**
     * Categoryレコードに紐づくMemoレコードを返す関数。
     */
    public function memos()
    {
        return $this->belongsToMany(
            Memo::class,
            CategoryMemo::class,
            'category_id',
            'memo_id'
        );
    }
}
