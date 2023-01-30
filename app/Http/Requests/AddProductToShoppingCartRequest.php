<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddProductToShoppingCartRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'productId' => ['required', 'exists:App\Models\Product,id'],
            'quantity' => ['required', 'integer', 'min: 1']
        ];
    }
}
