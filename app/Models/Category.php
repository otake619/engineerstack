<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'user_id',
        'memo_id',
        'created_user_id',
        'updated_user_id'
    ]

    public static function store(string $category, int $user_id, int $memo_id)
    {
        $memo = Memo::create([
            'category' => $category,
            'user_id' => $user_id,
            'memo_id' => $memo_id,
            'created_user_id' => $user_id
        ]);
    }
}
