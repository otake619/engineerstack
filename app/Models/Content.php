<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'json',
        'user_id',
        'memo_id',
        'created_user_id',
        'updated_user_id'
    ]

    $content = Content::create([
        'json' => $json,
        'user_id' => $user_id,
        'memo_id' => $memo_id,
        'created_user_id' => $user_id
    ]);
}
