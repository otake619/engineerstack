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

    protected function prepareForValidation() 
    {
    }

    /**
     * リクエストに適用するルール
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_flg' => ['accepted'],
            'categories' => ['required', 'string'],
            'categories_count' => ['required','integer' , 'max:5'],
            'memo' => ['required','string', 'max:5000']
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
            'category_flg.accepted' => 'カテゴリは20文字以内です。',
            'categories_count.max' => 'カテゴリの最大数は5つです。',
            'categories_count.required' => 'カテゴリは必須です。',
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
            'memo' => 'メモ',
            'categories' => 'カテゴリ',
            'categories_count' => 'カテゴリ数',
            'category_flg' => '1つのカテゴリ'
        ];
    }
}
