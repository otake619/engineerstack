<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:100',
            'memo' => 'json',
            'memo_count' => 'max:100'
        ];
    }

    public function messages()
    {
        return [
            'title.max:100' => 'カテゴリは100文字以内に収めてください。',
            'categories.required' => 'カテゴリは必ず1つの入力が必要です',
            'categories.*.max:30' => '各カテゴリは最大30文字です'
        ];
    }
}
