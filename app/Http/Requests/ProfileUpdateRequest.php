<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'username' => [
                'nullable',
                'string',
                'alpha_dash',
                'min:3',
                'max:30',
                Rule::unique('users')
                    ->ignore($this->user()->id),
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)
                    ->ignore($this->user()->id),
            ],

           'avatar' => [
    'nullable',
    'image',
    'mimes:jpg,jpeg,png,webp',
    'max:5120',
],
            'bio' => [
                'nullable',
                'string',
                'max:500',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'job_title' => [
                'nullable',
                'string',
                'max:100',
            ],

            'department' => [
                'nullable',
                'string',
                'max:100',
            ],

            'location' => [
                'nullable',
                'string',
                'max:150',
            ],

        ];
    }
}