<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'user_id',
        'created_user_id',
        'updated_user_id'
    ]

    public static function store(int $user_id, string $title)
    {
        $memo = Memo::create([
            'user_id' => $user_id,
            'title' => $title
        ]);
    }
}
