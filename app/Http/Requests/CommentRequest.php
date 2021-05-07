<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // ←falseからtrueに変更
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //名前：必須、40文字まで,コメント：必須、350文字まで
        return [
            'name' => 'required|max:40',
            'comment' => 'required|max:350',
        ];
    }

    /**
     * エラーメッセージを日本語化
     * 
     */
    public function messages()
    {
        return [
            'name.required' => '名前を入力してください',
            'name.max' => '名前は40文字以内で入力してください',
            'comment.required' => 'コメント本文を入力してください',
            'comment.max' => 'コメント本文は350文字以内で入力してください',
        ];
    }
}