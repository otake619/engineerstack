<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactSendmail;

class ContactController extends Controller
{
    /**
     * @param void
     * @return Illuminate\View\View
     * お問い合わせ画面を返します。
     */
    public function index()
    {
        return view('contact.index');
    }

    /**
     * お問い合わせ画面で入力された内容にバリデーションを
     * かけて、問題がなければお問い合わせ内容の確認画面を
     * 返します。入力内容に問題があった場合は、お問い合わせ画面
     * にリダイレクトし、エラー内容が表示されます。
     * 
     * @param Illuminate\Http\Request $request
     * name, email, category, bodyが入っています。
     * @return Illuminate\View\View
     * お問い合わせ内容の確認画面を返します。
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
     * 入力値にバリデーションをかけて、問題がなければ
     * メールを運営に送信します。送信後、お問い合わせ送信
     * 完了画面を返します。
     * @param Illuminate\Http\Request $request
     * name, email, category, body, actionが入っています。
     * @return Illuminate\View\View
     * お問い合わせ送信完了画面を返します。
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
            Mail::to($inputs['email'])->send(new ContactSendMail($inputs));
            $request->session()->regenerateToken();
            return view('contact.thanks');
        }
    }
}
