<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('vendor') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'sku'       => $this->input('sku') ?: null,
        ]);
    }

    public function rules(): array
    {
        $vendor  = $this->user()->vendor;
        $product = $this->route('product');

        return [
            'name'        => ['required', 'string', 'min:2', 'max:150'],
            'description' => ['nullable', 'string', 'max:5000'],
            'category_id' => [
                'required', 'integer',
                Rule::exists('categories', 'id')->where('is_active', true),
            ],
            'price'       => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'stock'       => ['required', 'integer', 'min:0', 'max:1000000'],
            'sku'         => [
                'nullable', 'string', 'max:64',
                Rule::unique('products', 'sku')
                    ->where('vendor_id', $vendor->id)
                    ->ignore($product?->id),
            ],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active'   => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.exists' => 'Selected category is invalid or inactive.',
            'price.min'          => 'Price must be at least 0.01 LYD.',
            'sku.unique'         => 'You already have a product with this SKU.',
        ];
    }
}