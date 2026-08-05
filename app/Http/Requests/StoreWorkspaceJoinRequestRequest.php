<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkspaceJoinRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'identity_card' => [
                'required',
                'image',
                'max:5120',
            ],

            'selfie_with_identity_card' => [
                'required',
                'image',
                'max:5120',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'identity_card.required' =>
                'Identity Card is required.',

            'selfie_with_identity_card.required' =>
                'Selfie with Identity Card is required.',
        ];
    }
}