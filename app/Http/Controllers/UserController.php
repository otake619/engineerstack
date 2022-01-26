<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class UserController extends Controller
{
    private $user;

    /**
     * コンストラクタでmiddleware('auth');
     * を設定しているので、ログイン前では
     * ユーザーに関するデータにはアクセスできません。
     * 
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = User::find(Auth::id());
        return view('EngineerStack.account', compact('user'));
    }

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
}
