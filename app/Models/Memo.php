<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'memo_data'
    ];

    public static function store(int $user_id, string $title, string $memo_data)
    {
        $memo = Memo::create([
            'user_id' => $user_id,
            'title' => $title,
            'memo_data' => $memo_data
        ]);

        return $memo->id;
    }
}
