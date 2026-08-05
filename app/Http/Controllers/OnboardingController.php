<?php

namespace App\Http\Controllers;

use App\Services\WorkspaceService;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    private const SESSION_KEY = 'onboarding';

    public function __construct(
        protected WorkspaceService $workspaceService,
    ) {
    }

    /**
     * Step 1 - Identity
     */
    public function identity()
    {
        return view('onboarding.identity', [
            'identity' => session(self::SESSION_KEY . '.identity', []),
        ]);
    }

    /**
     * Store Identity
     */
    public function storeIdentity(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'bio' => [
                'nullable',
                'string',
                'max:500',
            ],

            'logo' => [
                'nullable',
                'image',
                'max:5120',
            ],
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request
                ->file('logo')
                ->store('workspaces', 'public');
        }

        session([
            self::SESSION_KEY . '.identity' => $validated,
        ]);

        return redirect()->route('onboarding.privacy');
    }

    /**
     * Step 2 - Privacy
     */
    public function privacy()
    {
        return view('onboarding.privacy', [
            'privacy' => session(self::SESSION_KEY . '.privacy', []),
        ]);
    }

    /**
     * Store Privacy
     */
    public function storePrivacy(Request $request)
    {
        $validated = $request->validate([
            'visibility' => [
                'required',
                'in:public,private',
            ],
        ]);

        session([
            self::SESSION_KEY . '.privacy' => $validated,
        ]);

        return redirect()->route('onboarding.knowledge');
    }

    /**
     * Step 3 - Knowledge
     */
    public function knowledge()
    {
        return view('onboarding.knowledge', [
            'knowledge' => session(self::SESSION_KEY . '.knowledge', []),
        ]);
    }

    /**
     * Finish Onboarding
     */
    public function storeKnowledge(Request $request)
    {
        $identity = session(self::SESSION_KEY . '.identity');
        $privacy = session(self::SESSION_KEY . '.privacy');

        if (! $identity || ! $privacy) {
            return redirect()->route('onboarding.identity');
        }

        $this->workspaceService->create(
            $request->user(),
            [
                'name' => $identity['name'],
                'description' => $identity['bio'] ?? null,
                'logo' => $identity['logo'] ?? null,
                'visibility' => $privacy['visibility'],
            ]
        );

        $request->session()->forget(self::SESSION_KEY);

        return redirect()->route('dashboard');
    }
}