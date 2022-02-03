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
        'name'
    ];

    /**
     * テーブルにカテゴリーを保存して、保存したカテゴリーの
     * IDを取得。
     * @param string $name カテゴリーのname
     * @return int $category->id カテゴリーID
     */
    public static function store(string $name)
    {
        $category = Category::create([
            'name' => $name,
        ]);

        return $category->id;
    }

    /**
     * カテゴリーに紐づくメモを取得。
     * @return mixed Illuminate\Database\Eloquent\Relations\BelongsToMany 
     * メモのcollection
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
