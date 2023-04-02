<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRequest extends FormRequest
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

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(
            response()->json([
                'status' => 'fail',
                'message' => $validator->errors()->all()
            ],
                getStatusCodes('VALIDATION_ERROR'))
        );
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_name' => 'required|min:1|max:255',
            'price' => 'required|min:1|max:255',
            'stock' => 'min:1|max:255',
            'description' => 'min:1|max:255',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() {

        return [
            'product_name.required' => 'product_name_required',
            'product_name.max' => 'product_name_too_long',
            'product_name.min' => 'product_name_too_short',

            'price.required' => 'price_required',
            'price.max' => 'price_too_long',
            'price.min' => 'price_too_short',

            'stock.required' => 'stock_required',
            'stock.min' => 'stock_too_short',
            'stock.max' => 'stock_too_long',

            'description.required' => 'description_required',
            'description.min' => 'description_too_short',
            'description.max' => 'description_too_long',

        ];
    }
}
