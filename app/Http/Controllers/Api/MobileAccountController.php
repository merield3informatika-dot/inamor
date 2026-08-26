<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

final class MobileAccountController extends Controller
{
    public function __construct(
        private readonly UserProfileService $userProfileService,
    ) {
    }

    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'user' => $this->formatUser($user),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
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
                    ->ignore($user->id),
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')
                    ->ignore($user->id),
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
        ]);

        if (
            isset($validated['email']) &&
            $user->email !== $validated['email']
        ) {
            $validated['email_verified_at'] = null;
        }

        $user = $this->userProfileService->update(
            $user,
            $validated,
        );

        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'user' => $this->formatUser($user),
        ]);
    }

    private function formatUser($user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'email_verified_at' =>
                $user->email_verified_at?->toISOString(),

            'avatar_url' => $user->avatar
                ? asset('storage/' . $user->avatar)
                : null,

            'bio' => $user->bio,
            'phone' => $user->phone,
            'job_title' => $user->job_title,
            'department' => $user->department,
            'location' => $user->location,

            'current_workspace_id' =>
                $user->current_workspace_id,

            'created_at' =>
                $user->created_at?->toISOString(),

            'updated_at' =>
                $user->updated_at?->toISOString(),
        ];
    }
}