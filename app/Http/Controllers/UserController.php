<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
}
