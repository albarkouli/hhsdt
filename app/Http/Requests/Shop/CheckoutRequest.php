<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'shipping_name'    => ['required', 'string', 'min:2', 'max:100'],
            'shipping_phone'   => ['required', 'string', 'regex:/^[0-9+\-\s()]{6,30}$/'],
            'shipping_city'    => ['required', 'string', 'max:100'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'notes'            => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'shipping_name.required'    => 'Full name is required.',
            'shipping_phone.required'   => 'Phone number is required.',
            'shipping_phone.regex'      => 'Phone number format is invalid.',
            'shipping_city.required'    => 'City is required.',
            'shipping_address.required' => 'Delivery address is required.',
        ];
    }
}