<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectWorkspaceJoinRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'reason' => [
                'required',
                'string',
                'max:500',
            ],
        ];
    }
}