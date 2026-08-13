<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => 'required|string|max:255',
            'phone'           => 'required|digits_between:10,15',
            'alternate_phone' => 'nullable|digits_between:10,15',
            'line1'           => 'required|string|max:255',
            'line2'           => 'required|string|max:255',
            'landmark'        => 'nullable|string|max:255',
            'city'            => 'required|string|max:100',
            'state'           => 'required|string|max:100',
            'pincode'         => 'required|digits:6',
            'type'            => 'nullable|in:home,work,other',
            'is_default'      => 'nullable|boolean',
        ];
    }
}
