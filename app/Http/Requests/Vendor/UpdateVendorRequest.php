<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->vendor !== null;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => $this->input('email') ?: null,
        ]);
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'min:3', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
            'phone'       => ['required', 'string', 'regex:/^[0-9+\-\s()]{6,30}$/'],
            'email'       => ['nullable', 'email:rfc', 'max:150'],
            'address'     => ['nullable', 'string', 'max:255'],
            'city'        => ['required', 'string', 'max:100'],
            'logo'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'Store name is required.',
            'name.min'       => 'Store name must be at least 3 characters.',
            'phone.required' => 'A contact phone number is required.',
            'phone.regex'    => 'Phone number format is invalid.',
            'city.required'  => 'Please enter a city.',
            'logo.image'     => 'The logo must be an image file.',
            'logo.max'       => 'Logo must be under 2MB.',
        ];
    }
}