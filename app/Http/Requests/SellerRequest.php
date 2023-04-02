<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SellerRequest extends FormRequest
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
            'seller_name' => 'required|min:1|max:255',
            'email' => 'required|min:2|max:150',
            'password' => 'required|min:8|max:24',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() {

        return [
            'seller_name.required' => 'seller_name_required',
            'seller_name.max' => 'seller_name_too_long',
            'seller_name.min' => 'seller_name_too_short',

            'email.required' => 'email_required',
            'email.max' => 'email_too_long',
            'email.min' => 'email_too_short',

            'password.required' => 'password_required',
            'password.min' => 'password_too_short',
            'password.max' => 'password_too_long',

        ];
    }
}
