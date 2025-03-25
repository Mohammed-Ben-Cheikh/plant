<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'invoice' => 'required|string|max:255|unique:orders,invoice',
            'user_id' => 'required|integer|exists:users,id',
            'plant_id' => 'required|integer|exists:plants,id',
            'quantity' => 'required|numeric|min:1',
            'total' => 'required|numeric|min:0',
        ];
    }
}
