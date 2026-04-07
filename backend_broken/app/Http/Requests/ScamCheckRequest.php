<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScamCheckRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'message_text' => ['nullable', 'string', 'max:5000'],
            'url'          => ['nullable', 'string', 'max:2048'],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'bank_account' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $fields = ['message_text', 'url', 'phone_number', 'bank_account'];
            $filled = array_filter($fields, fn ($f) => filled($this->input($f)));
            if (count($filled) === 0) {
                $v->errors()->add('general', 'Please provide at least one field to check.');
            }
        });
    }
}
