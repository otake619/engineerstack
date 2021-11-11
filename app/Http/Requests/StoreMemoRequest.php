<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemoRequest extends FormRequest
{
    /**
     * 現在認証されているユーザーがリクエストによって
     * 表されるアクションを実行できるか
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * リクエストに適用するルール
     *
     * @return array
     */
    public function rules()
    {
        return [
            'categories' => 'required|max:154',
            'categories_count' => 'required|regex:/[1-5]/',
            'title' => 'required|max:100',
            'memo_count' => 'required|max:3000',
        ];
    }

    /**
     * バリデーションエラーのカスタムメッセージ
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.max:100' => 'カテゴリは100文字以内に収めてください。',
            'categories_count.regex' => 'カテゴリの最大数は5つです。'
        ];
    }

    /**
     * バリデーションエラーのカスタム属性の取得
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'title' => 'メモのタイトル',
            'memo_count' => 'メモの文字数',
            'categories' => 'カテゴリ'
        ];
    }
}
