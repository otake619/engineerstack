<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $user;

    /**
     * ログイン前ではユーザーに関するデータには
     * アクセスできません。
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * アカウント画面を返す。
     * @param void
     * @return Illuminate\View\View
     */
    public function show()
    {
        $user = User::find(Auth::id());
        return view('EngineerStack.account', compact('user'));
    }

    /**
     * アカウント名の更新処理。トランザクション処理が正常に完了した場合は、
     * アカウント画面を返し、問題があった場合は処理を中断しアカウント画面
     * に通知を表示する。
     * @param Illuminate\Http\Request $request
     * @return Illuminate\View\View
     * アカウント画面を返す。
     */
    public function updateAccountName(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
        ]);
        $name = $request->input('name');

        DB::beginTransaction();

        try {
            User::where('id', Auth::id())->update(['name' => $name]);
            $user = User::find(Auth::id());
            $message = "アカウント名を更新しました。";
            DB::commit();
            return view('EngineerStack.account', compact('message', 'user'));
        } catch(Exception $exception) {
            DB::rollback();
            return redirect()->route('user.show')->with('message', 'アカウントの編集に失敗しました。');
        }

    }

    /**
     * パスワードの更新処理。
     * @param Illuminate\Http\Request $request
     * @return Illuminate\View\View
     * アカウント画面を返す。
     */
    public function updatePassWord(Request $request)
    {
        $user = Auth::user();
        $old_password = $request->input('old_password');
        $password_check = Hash::check($old_password, $user->password);
        if(!$password_check) {
            return redirect()->route('user.show')
                    ->with('alert', '正しいパスワードを入力してください。');
        }
        $new_password = $request->input('new_password');
        $validated = $request->validate([
            'new_password' => 'required|min:8|max:16|confirmed',
        ]);
        $user->update(['password' => bcrypt($new_password)]);
        return redirect()->route('user.show')
                ->with('message', 'パスワードを変更しました。');
    }

    /**
     * アカウントの削除
     * @param void
     * @return Illuminate\Support\Facades\Redirect;
     */
    public function destroy() 
    {
        $user = Auth::user();

        DB::beginTransaction();
        try {
            $user->delete();
            Auth::logout();
            DB::commit();
            return redirect()->route('register')->with('message', '退会処理が完了しました。');
        } catch(Exception $exception){
            DB::rollback();
            return redirect()->route('user.show')->with('message', '退会に失敗しました。');
        }
    }
}
