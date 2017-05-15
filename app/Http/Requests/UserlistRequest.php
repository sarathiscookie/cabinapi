<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserlistRequest extends FormRequest
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
            'usrFirstname' => 'required|max:100',
            'usrLastname'  => 'required|max:100',
            'usrEmail'     => 'required|email|unique:user|max:255'
        ];
    }
}
