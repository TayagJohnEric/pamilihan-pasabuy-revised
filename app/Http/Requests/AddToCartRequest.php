<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        $product = Product::find($this->product_id);
        
        if (!$product) {
            return [
                'product_id' => 'required|exists:products,id',
            ];
        }

        $rules = [
            'product_id' => 'required|exists:products,id',
        ];

        if ($product->is_budget_based) {
            $rules['customer_budget'] = 'required|numeric|min:0.01|max:999999.99';
            $rules['customer_notes'] = 'nullable|string|max:500';
        } else {
            $rules['quantity'] = [
                'required',
                'integer',
                'min:1',
                'max:' . $product->quantity_in_stock
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'customer_budget.required' => 'Budget amount is required for this item.',
            'customer_budget.min' => 'Budget must be at least ₱0.01.',
            'customer_budget.max' => 'Budget cannot exceed ₱999,999.99.',
            'quantity.max' => 'Quantity cannot exceed available stock.',
        ];
    }
}