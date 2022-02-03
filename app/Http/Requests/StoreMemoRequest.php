<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemoRequest extends FormRequest
{
    /**
     * 現在認証されているユーザーがリクエストによって
     * 表されるアクションを実行できるか
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * リクエストに適用するルール
     * @return array
     */
    public function rules()
    {
        return [
            'categories' => ['required', 'string', 'max:200'],
            'memo' => ['required','string', 'max:5000']
        ];
    }

    /**
     * バリデーションエラーのカスタムメッセージ
     * @return array
     */
    public function messages()
    {
        return [
            'categories.max' => 'カテゴリの文字数が多すぎます。',
        ];
    }

    /**
     * バリデーションエラーのカスタム属性の取得
     * @return array
     */
    public function attributes()
    {
        return [
            'memo' => 'メモ',
            'categories' => 'カテゴリ',
        ];
    }
}
