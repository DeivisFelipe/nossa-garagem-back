<?php

namespace App\Http\Requests;

class AddressRequest extends BaseRequest
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
                    'cep' => 'required|min:9|max:9|string',
                    'street' => 'required|min:1|max:255|string',
                    'city' => 'required|min:1|max:255|string',
                    'number' => 'required|integer',
                ];
                break;
            case 'PUT':
            case 'PATCH':
                return [
                    'cep' => 'min:9|max:9|string',
                    'street' => 'min:1|max:255|string',
                    'city' => 'min:1|max:255|string',
                    'number' => 'integer',
                ];
                break;
        }
    }
}
