<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\EmailReset;
use App\Models\User;
use Carbon\Carbon;

class ChangeEmailController extends Controller
{
    public function sendChangeEmailLink(Request $request)
    {
        $new_email = $request->input('new_email');
        $token = hash_hmac(
            'sha256',
            Str::random(40) . $new_email,
            config('app.key')
        );

        DB::beginTransaction();
        try {
            $param = [];
            $param['user_id'] = Auth::id();
            $param['new_email'] = $new_email;
            $param['token'] = $token;
            $email_reset = EmailReset::create($param);

            DB::commit();

            $email_reset->sendEmailResetNotification($token);

            return redirect()->route('user.show')->with('message', '確認メールを送信しました。');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('user.show')->with('alert', 'メール更新に失敗しました。');
        }
    }

    /**
     * メールアドレスの再設定
     * 
     * @param Request $request
     * @param[type] $token
     */
    public function reset($token)
    {
        $email_resets = DB::table('email_resets')
        ->where('token', $token)
        ->first();

        if($email_resets && !$this->tokenExpired($email_resets->created_at)) {
            $user = User::find($email_resets->user_id);
            $user->email = $email_resets->new_email;
            $user->save();

            DB::table('email_resets')
            ->where('token', $token)
            ->delete();

            return redirect()->route('user.show')->with('message', 'メールアドレスを更新しました。');
        } else {
            if($email_resets) {
                DB::table('email_resets')
                    ->where('token', $token)
                    ->delete();
            }
            return redirect()->route('user.show')->With('alert', 'メールアドレスの更新に失敗しました。');
        }
    }

    /**
     * トークンが有効期限切れが調べる
     * 
     * @param string $createdAt
     * @return bool
     */
    protected function tokenExpired($createdAt)
    {
        $expires = 60 * 60;
        return Carbon::parse($createdAt)->addSeconds($expires)->isPast();
    }
}
