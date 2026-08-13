<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'address_id'      => 'nullable|exists:addresses,id',
            'name'            => 'required_without:address_id|string|max:255',
            'phone'           => 'required_without:address_id|digits_between:10,15',
            'alternate_phone' => 'nullable|digits_between:10,15',
            'line1'           => 'required_without:address_id|string|max:255',
            'line2'           => 'required_without:address_id|string|max:255',
            'landmark'        => 'nullable|string|max:255',
            'city'            => 'required_without:address_id|string|max:100',
            'state'           => 'required_without:address_id|string|max:100',
            'pincode'         => 'required_without:address_id|digits:6',
            'payment_method'  => 'required|in:cod,razorpay',
        ];
    }
}
