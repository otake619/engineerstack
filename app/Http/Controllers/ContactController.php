<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactSendMail;
use Illuminate\Support\Facades\Mail;
use Exception;

class ContactController extends Controller
{
    /**
     * お問い合わせ画面を返す。
     * @param void
     * @return Illuminate\View\View お問い合わせ画面
     */
    public function index()
    {
        return view('contact.index');
    }

    /**
     * バリデーションを実行し、入力値の確認画面を返す。
     * 入力値に問題があれば、お問い合わせ画面でエラーを表示。
     * @param Illuminate\Http\Request $request メールの差出人,アドレス,相談種別,本文
     * @return Illuminate\View\View お問い合わせ内容の確認画面
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'category' => 'required',
            'body'  => 'required',
        ]);
        
        $inputs = $request->all();

        return view('contact.confirm', [
            'inputs' => $inputs,
        ]);
    }

    /**
     * バリデーションをかけて、メールを送信し完了画面を表示。
     * 入力値に問題があった場合はエラーが表示される。
     * @param Illuminate\Http\Request $request
     * メールの差出人,アドレス,相談種別,本文,アクション
     * @return Illuminate\View\View お問い合わせ送信完了画面
     */
    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'category' => 'required',
            'body'  => 'required'
        ]);

        $action = $request->input('action');
        $inputs = $request->except('action');

        if($action !== 'submit'){
            return redirect()
                ->route('contact.index')
                ->withInput($inputs);

        } else {
            try {
                $admin_email = "ryoheiotake@gmail.com";
                Mail::to($admin_email)->send(new ContactSendMail($inputs));
                Mail::to($inputs['email'])->send(new ContactSendMail($inputs));
                $request->session()->regenerateToken();
                return view('contact.thanks');
            } catch(Exception $e) {
                return redirect()
                    ->route('contact.index')
                    ->with('alert', 'お問い合わせに失敗しました。');
            }
        }
    }
}