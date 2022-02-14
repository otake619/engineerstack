<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Google2FA;

class AdminHomeController extends Controller
{
    /**
     * 管理者ホーム画面を表示
     * @param void
     * @return Illuminate\View\View 管理者ホーム画面
     */
    public function index()
    {
        $g2fa = app('pragmarx.google2fa');
        $qr = $g2fa->getQRCodeInline(
            config('app.name'),
            Auth::guard('admin')->user()->email,
            Auth::guard('admin')->user()->google2fa_secret,
        );
        return view('admin.dashboard', compact('qr'));
    }
}
