<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VenteFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        'stock_id' => ['required'],
        'client_id' => ['required'],
        'quantite' => 'required|integer|min:1',
        'prix_unitaire' => 'required|numeric|min:0',
        'date_vente' => ['required'],
        ];
    }
}
