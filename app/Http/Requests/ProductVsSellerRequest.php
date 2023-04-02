<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductVsSellerRequest extends FormRequest
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
            'product_id' => 'required|integer',
            'seller_id' => 'required|integer',
            'seller_price' => 'required|min:1|max:255',
            'seller_stock' => 'min:1|max:255',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() {

        return [
            'product_id.required' => 'product_id_required',
            'product_id.max' => 'product_id_should_be_integer',

            'seller_id.required' => 'seller_id_required',
            'seller_id.max' => 'seller_id_should_be_integer',

            'seller_price.required' => 'seller_price_required',
            'seller_price.max' => 'seller_price_too_long',
            'seller_price.min' => 'seller_price_too_short',

            'seller_stock.required' => 'seller_stock_required',
            'seller_stock.min' => 'seller_stock_too_short',
            'seller_stock.max' => 'seller_stock_too_long',

        ];
    }
}
