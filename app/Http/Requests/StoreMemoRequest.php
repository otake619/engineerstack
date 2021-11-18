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
            'categories_count.regex' => 'カテゴリの最大数は5つです。',
            'categories_count.required' => 'カテゴリは必須です。'
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
            'memo_count' => 'メモの文字数',
            'categories' => 'カテゴリ',
        ];
    }
}
