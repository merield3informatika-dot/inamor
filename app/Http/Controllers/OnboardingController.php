<?php

namespace App\Http\Controllers;

use App\Services\WorkspaceService;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    private const SESSION_KEY = 'onboarding';

    public function __construct(
        protected WorkspaceService $workspaceService
    ) {}

 public function identity(Request $request)
{
    if ($request->user()->current_workspace_id) {
        return redirect()->route('dashboard');
    }

    return view('onboarding.identity', [
        'identity' => session(self::SESSION_KEY . '.identity', []),
    ]);
}

    public function storeIdentity(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:500'],
        ]);

        session([
            self::SESSION_KEY . '.identity' => $validated,
        ]);

        return redirect()->route('onboarding.privacy');
    }

    public function privacy()
    {
        return view('onboarding.privacy', [
            'privacy' => session(self::SESSION_KEY . '.privacy', []),
        ]);
    }

    public function storePrivacy(Request $request)
    {
        $validated = $request->validate([
            'visibility' => ['required', 'in:public,private'],
        ]);

        session([
            self::SESSION_KEY . '.privacy' => $validated,
        ]);

        return redirect()->route('onboarding.knowledge');
    }

    public function knowledge()
    {
        return view('onboarding.knowledge', [
            'knowledge' => session(self::SESSION_KEY . '.knowledge', []),
        ]);
    }

    public function storeKnowledge(Request $request)
    {
        $identity = session(self::SESSION_KEY . '.identity');
        $privacy = session(self::SESSION_KEY . '.privacy');

        if (! $identity || ! $privacy) {
            return redirect()->route('onboarding.identity');
        }

        $this->workspaceService->create(
            $request->user(),
            $identity['name']
        );

        $request->session()->forget(self::SESSION_KEY);

        return redirect()->route('dashboard');
    }
}