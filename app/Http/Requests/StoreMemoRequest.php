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
            'memo_count' => ['required','integer', 'max:1000']
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
            'memo_count.max' => 'メモの最大入力文字を超えています。',
            'memo_count.required' => 'メモの入力は必須です。'
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
            'categories_count' => 'カテゴリ数',
            'category_flg' => '1つのカテゴリ'
        ];
    }
}
