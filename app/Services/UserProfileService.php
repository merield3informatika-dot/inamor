<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserProfileService
{
    public function __construct(
        protected UserRepository $repository,
    ) {
    }

    /**
     * Update user profile.
     */
    public function update(
        User $user,
        array $data,
    ): User {

        /*
        |--------------------------------------------------------------------------
        | Upload Avatar
        |--------------------------------------------------------------------------
        */

        if (
            array_key_exists('avatar', $data) &&
            $data['avatar'] instanceof UploadedFile
        ) {

            /*
            |--------------------------------------------------------------------------
            | Delete old avatar
            |--------------------------------------------------------------------------
            */

            if (
                ! empty($user->avatar) &&
                Storage::disk('public')->exists($user->avatar)
            ) {
                Storage::disk('public')->delete(
                    $user->avatar,
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Store new avatar
            |--------------------------------------------------------------------------
            */

            $data['avatar'] = $data['avatar']->store(
                'avatars',
                'public',
            );
        } else {

            /*
            |--------------------------------------------------------------------------
            | Prevent overriding avatar with null
            |--------------------------------------------------------------------------
            */

            unset($data['avatar']);
        }

        return $this->repository->update(
            $user,
            $data,
        );
    }

    /**
     * Find profile by username.
     */
    public function findByUsername(
        string $username,
    ): ?User {

        return $this->repository->findByUsername(
            $username,
        );
    }

    /**
     * Find profile by ID.
     */
    public function find(
        int $id,
    ): ?User {

        return $this->repository->find(
            $id,
        );
    }
}