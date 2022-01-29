<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\ChangeEmail;
use Illuminate\Notifications\Notifiable;

class EmailReset extends Model
{
    use Notifiable;

    protected $fillable = [
        'user_id',
        'new_email',
        'token',
    ];

    /**
     * メールアドレス変更確定メールを送信
     * @param [type] $token
     */
    public function sendEmailResetNotification($token)
    {
        $this->notify(new ChangeEmail($token));
    }

    /**
     * 新しいメールアドレス宛にメール送信
     * @param Illuminate\Notifications\Notification $notification
     * @return string
     * 
     */
    public function routeNotificationForMail($notification)
    {
        return $this->new_email;
    }
}
