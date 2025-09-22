<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Public application form should accept submissions without auth context
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
            'first_name'     => ['required','string','max:100'],
            'last_name'      => ['required','string','max:100'],
            'email'          => ['required','email','max:190'],
            'phone'          => ['nullable','string','max:50'],
            'ghana_card_no'  => ['nullable','string','max:50'],
            'passport_no'    => ['nullable','string','max:50'],
        ];
    }
}
