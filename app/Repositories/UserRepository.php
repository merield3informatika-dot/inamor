<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * Find user by ID.
     */
    public function find(
        int $id,
    ): ?User {

        return User::query()->find($id);
    }

    /**
     * Find user by username.
     */
    public function findByUsername(
        string $username,
    ): ?User {

        return User::query()
            ->where('username', $username)
            ->first();
    }

    /**
     * Update user.
     */
    public function update(
        User $user,
        array $data,
    ): User {

        $user->update($data);

        return $user->fresh();
    }
}