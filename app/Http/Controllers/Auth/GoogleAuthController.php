<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

final class GoogleAuthController extends Controller
{
    /**
     * Redirect user to Google OAuth.
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')
            ->scopes([
                'openid',
                'profile',
                'email',
            ])
            ->redirect();
    }

    /**
     * Handle Google OAuth callback.
     */
    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->user();
        } catch (Throwable $e) {
            report($e);

            return redirect('/login')
                ->with(
                    'error',
                    'Google authentication gagal. Silakan coba lagi.',
                );
        }

        $googleId = $googleUser->getId();
        $email = $googleUser->getEmail();
        $name = $googleUser->getName()
            ?: $googleUser->getNickname()
            ?: 'Google User';

        /*
        |--------------------------------------------------------------------------
        | Google ID wajib tersedia
        |--------------------------------------------------------------------------
        */

        if (! $googleId || ! $email) {
            return redirect('/login')
                ->with(
                    'error',
                    'Data akun Google tidak lengkap.',
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Find existing user
        |--------------------------------------------------------------------------
        |
        | Prioritas:
        |
        | 1. google_id
        | 2. email
        |
        */

        $user = User::query()
            ->where('google_id', $googleId)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Existing account by email
        |--------------------------------------------------------------------------
        |
        | Kalau user sebelumnya sudah register menggunakan
        | email/password yang sama, kita hubungkan akun Google
        | ke user tersebut.
        |
        */

        if (! $user) {
            $user = User::query()
                ->where('email', $email)
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | Create / update user
        |--------------------------------------------------------------------------
        */

        if ($user) {
            $user->update([
                'google_id' => $googleId,
                'name' => $user->name ?: $name,
                'email_verified_at' =>
                    $user->email_verified_at
                    ?? now(),
            ]);
        } else {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'google_id' => $googleId,

                /*
                |--------------------------------------------------------------------------
                | Google users don't authenticate with this password.
                |--------------------------------------------------------------------------
                */

                'password' => Hash::make(
                    Str::random(64)
                ),

                'email_verified_at' => now(),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Create Sanctum token
        |--------------------------------------------------------------------------
        */

        $token = $user
            ->createToken('mobile')
            ->plainTextToken;

        /*
        |--------------------------------------------------------------------------
        | Temporary callback response
        |--------------------------------------------------------------------------
        |
        | Untuk tahap awal kita kirim token sebagai query parameter.
        |
        | Nanti setelah React flow selesai, kita ganti menjadi
        | callback page yang lebih aman agar token tidak perlu
        | dipertahankan di URL.
        |
        */

        $frontendUrl = config(
            'app.mobile_url',
            'http://localhost:5173',
        );

        return redirect()->away(
            $frontendUrl
            . '/auth/google/callback?token='
            . urlencode($token)
        );
    }
}