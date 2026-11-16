<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('super_admin') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code'      => $this->input('code') ? strtoupper(trim($this->input('code'))) : null,
            'is_active' => $this->boolean('is_active'),
            'max_uses'  => $this->input('max_uses') ?: null,
            'max_discount' => $this->input('max_discount') ?: null,
            'starts_at' => $this->input('starts_at') ?: null,
            'expires_at' => $this->input('expires_at') ?: null,
        ]);
    }

    public function rules(): array
    {
        $id = $this->route('coupon')?->id;

        return [
            'code'              => ['required', 'string', 'min:3', 'max:30', 'regex:/^[A-Z0-9_-]+$/', Rule::unique('coupons', 'code')->ignore($id)],
            'name'              => ['required', 'string', 'min:3', 'max:100'],
            'description'       => ['nullable', 'string', 'max:1000'],
            'type'              => ['required', 'in:percent,fixed'],
            'value'             => ['required', 'numeric', 'min:0.01', 'max:' . ($this->input('type') === 'percent' ? 100 : 99999)],
            'min_order'         => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'max_discount'      => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'max_uses'          => ['nullable', 'integer', 'min:1', 'max:1000000'],
            'max_uses_per_user' => ['required', 'integer', 'min:1', 'max:100'],
            'starts_at'         => ['nullable', 'date'],
            'expires_at'        => ['nullable', 'date', 'after:starts_at'],
            'is_active'         => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.regex' => 'Code can only contain uppercase letters, numbers, hyphens, and underscores.',
            'value.max'  => 'Percentage discount cannot exceed 100%.',
        ];
    }
}