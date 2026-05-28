<?php

namespace App\Http\Requests\Admin;

use App\Enums\PreventivoStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePreventivoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::in(PreventivoStatus::values())],
            'notes' => ['nullable', 'string', 'max:5000'],
            'follow_up_at' => [
                Rule::requiredIf(fn () => $this->input('status') === PreventivoStatus::RicontattareCliente->value),
                'nullable',
                'date',
                'after:now',
            ],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'follow_up_at.required' => 'Indica data e ora per ricontattare il cliente.',
            'follow_up_at.after' => 'La data di ricontatto deve essere nel futuro.',
        ];
    }
}
