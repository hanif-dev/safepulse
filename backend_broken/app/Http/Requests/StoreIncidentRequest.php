<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIncidentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'category'               => ['required', 'in:phishing,investment,romance,radicalization,money_laundering,other'],
            'country'                => ['required', 'string', 'max:100'],
            'age_group'              => ['nullable', 'in:under_18,18_24,25_34,35_44,45_54,55_64,65_plus,prefer_not_to_say'],
            'description'            => ['required', 'string', 'min:20', 'max:3000'],
            'health_impact_level'    => ['required', 'in:low,medium,high'],
            'financial_loss_estimate'=> ['nullable', 'numeric', 'min:0', 'max:99999999'],
        ];
    }
}
