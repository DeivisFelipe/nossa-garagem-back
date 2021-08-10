<?php

namespace App\Http\Requests;

class GarageRequest extends BaseRequest
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
                    'address_id' => 'required|integer',
                    'image' => 'image',
                    'price' => 'required|numeric',
                    'description' => 'required|min:3|max:500|string',
                ];
                break;
            case 'PUT':
            case 'PATCH':
                return [
                    'address_id' => 'integer',
                    'image' => 'image',
                    'price' => 'numeric',
                    'description' => 'min:3|max:500|string',
                ];
                break;
        }
    }
}
