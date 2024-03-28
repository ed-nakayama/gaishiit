<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyRequest extends FormRequest
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
			'name' => [
				'required',
				'max:40',
			],
			'email' => [
            	'required',
            	'max:40',
            	'email',
				Rule::unique('companies')->ignore($this->id),
			],
 			'password' => [
     			'required',
     			'min:8',
     			'max:100',
			],
       ];
    }


    
    public function messages(){
        return [
            'name.required'  => '企業名を入力してください。',
            'name.max'  => '企業名は40文字以内を入力してください。',
            'name.max'  => '企業名が最大文字数を超えています。',
            'email.required'  => 'メールアドレスを入力してください。',
            'email.max'  => 'メールアドレスは最大文字数を超えています。',
            'email.unique'  => 'メールアドレスは既に存在しています。',
            'passwword.required'  => 'パスワードを入力してください。',
            'passwword.min'  => 'パスワードは8文字以上を入力してください。',
            'passwword.max'  => 'パスワードは40文字以内を入力してください。',
        ];
    }

}
