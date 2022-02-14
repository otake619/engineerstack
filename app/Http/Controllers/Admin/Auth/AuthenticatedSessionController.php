<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Google2FA;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $g2fa = app('pragmarx.google2fa');
        $g2fa_key = Auth::guard('admin')->user()->google2fa_secret;
        $one_time_password = $request->input('one_time_password');
        if (!$g2fa->verifyGoogle2FA($g2fa_key, $one_time_password)) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')->with('alert', 'ワンタイムパスワードが間違っています。');
        }

        $message = "ログインが完了しました。";
        $qr = $g2fa->getQRCodeInline(
            config('app.name'),
            Auth::guard('admin')->user()->email,
            Auth::guard('admin')->user()->google2fa_secret,
        );
        return view('admin.dashboard', compact('message', 'qr'));
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login')->with('message', 'ログアウトが完了しました。');
    }
}
