<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\UserProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        protected UserProfileService $userProfileService,
    ) {
    }

    /**
     * Display profile page.
     */
    public function edit(
        Request $request,
    ): View {

        return view(
            'profile.edit',
            [
                'user' => $request->user(),
            ],
        );
    }

    /**
     * Update profile.
     */
    public function update(
        ProfileUpdateRequest $request,
    ): RedirectResponse {

        $user = $request->user();

        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Avatar Upload
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar');
        }

        /*
        |--------------------------------------------------------------------------
        | Reset email verification if email changed
        |--------------------------------------------------------------------------
        */

        if (
            $user->email !== $data['email']
        ) {
            $data['email_verified_at'] = null;
        }

        $this->userProfileService->update(
            $user,
            $data,
        );

        return Redirect::route(
            'profile.edit'
        )->with(
            'status',
            'profile-updated',
        );
    }

    /**
     * Delete account.
     */
    public function destroy(
        Request $request,
    ): RedirectResponse {

        $request->validateWithBag(
            'userDeletion',
            [
                'password' => [
                    'required',
                    'current_password',
                ],
            ],
        );

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function show(
    string $username,
): View {

   $user = $this->userProfileService
    ->findByUsername(
        $username
    );

abort_if(
    ! $user,
    404,
);

return view(
    'people.show',
    compact('user'),
);
}
}