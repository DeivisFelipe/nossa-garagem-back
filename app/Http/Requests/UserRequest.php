<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UserRequest extends BaseRequest
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
        switch($this->method())
        {
            case 'POST':
                return [
                    'email' => 'required|min:3|max:255|email:rfc,dns|unique:users',
                    'name' => 'required|min:3|max:255|string',
                    'password' => 'required|min:6|max:255|string',
                    'phone' => 'min:9|max:14|string',
                ];
                break;
            case 'PUT':
            case 'PATCH':
                $id = $this->route('user');
                return [
                    'email' => [
                        'min:3',
                        'max:255',
                        'email:rfc,dns',
                        Rule::unique('users','email')->ignore($id,'id'),
                    ],
                    'password' => 'min:6|max:255|string',
                    'name' => 'min:3|max:255|string',
                    'phone' => 'min:9|max:14|string',
                ];
                break;
        }
    }
}
