<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        return view('auth.register', [
            'intent' => $request->query('intent', 'create'),
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'intent' => [
                'required',
                Rule::in(['create', 'join']),
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class,
            ],

            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ]);

       $user = User::create([
    'name' => $request->name,
    'email' => $request->email,

    'username' => $this->generateUsername(
        $request->name
    ),

    'password' => Hash::make(
        $request->password
    ),
]);
        event(new Registered($user));

        Auth::login($user);

        // Untuk sementara masih sama seperti Breeze.
        // Nanti di Step 2 kita ubah flow setelah verifikasi email.
       return redirect()->route('verification.notice');
    }
  private function generateUsername(string $name): string
{
    $base = Str::of(trim($name))
        ->lower()
        ->ascii()
        ->replaceMatches('/[^a-z0-9]+/', '')
        ->value();

    if ($base === '') {
        $base = 'user';
    }

    $base = substr($base, 0, 30);

    $username = $base;
    $i = 1;

    while (
        User::where('username', $username)->exists()
    ) {
        $username = $base . $i;
        $i++;
    }

    return $username;
}
}