<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryMemo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'memo_id',
        'category_id'
    ];

    public static function store(int $memo_id, int $category_id)
    {
        $category_memo = CategoryMemo::create([
            'memo_id' => $memo_id,
            'category_id' => $category_id
        ]);
    }
}
