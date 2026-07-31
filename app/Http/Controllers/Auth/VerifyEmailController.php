<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
   public function __invoke(EmailVerificationRequest $request): RedirectResponse
{
    if (! $request->user()->hasVerifiedEmail()) {
        $request->user()->markEmailAsVerified();

        event(new Verified($request->user()));
    }

    $user = $request->user();

    if (! $user->current_workspace_id) {
return redirect()->route('onboarding.identity');
    }

    return redirect()->route('dashboard');
}
}
