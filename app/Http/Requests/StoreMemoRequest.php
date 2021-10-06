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
            'categories' => 'required|array',
            'categories.*' => 'nullable|max:30'
            'title' => 'required|max:100',
            'memo' => 'required|json'
        ];
    }

    public function messages()
    {
        return [
            'categories.required' => 'カテゴリは必ず1つの入力が必要です',
            'categories.*.max:30' => '各カテゴリは最大30文字です'
        ];
    }
}
