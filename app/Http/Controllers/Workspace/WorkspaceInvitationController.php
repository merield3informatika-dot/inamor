<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Services\WorkspaceInvitationService;
use App\Services\WorkspaceMemberService;
use App\Services\WorkspaceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkspaceInvitationController extends Controller
{
    public function __construct(
        protected WorkspaceInvitationService $invitationService,
        protected WorkspaceMemberService $workspaceMemberService,
        protected WorkspaceService $workspaceService,
    ) {
    }

    /**
     * Display invitation page.
     */
public function show(Request $request): View
{
    $workspace = $this->workspaceService
        ->resolveActive($request->user());

    $invitation = $workspace->invitation;

    if (! $invitation) {

        $invitation = $this->invitationService->create(
            $workspace,
            $request->user()
        );
    }

    return view('workspaces.invitation.show', [
        'workspace' => $workspace,
        'invitation' => $invitation,
    ]);
}

    /**
     * Regenerate invitation token.
     */
    public function regenerate(
        Request $request
    ): RedirectResponse {

        $workspace = $this->workspaceService
            ->resolveActive($request->user());

        $invitation = $workspace->invitation;

        abort_if(
            ! $invitation,
            404,
            'Invitation not found.'
        );

        $this->invitationService
            ->regenerate($invitation);

        return back()->with(
            'status',
            'Invitation link regenerated successfully.'
        );
    }

    /**
     * Accept invitation.
     */
    public function accept(
        Request $request,
        string $token
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Login Required
        |--------------------------------------------------------------------------
        */

        if (! auth()->check()) {

            $request->session()->put(
                'workspace_invitation_token',
                $token
            );

            return redirect()->route('login');
        }

        $invitation = $this->invitationService
            ->findByToken($token);

        abort_if(
            ! $invitation,
            404,
            'Invitation not found.'
        );

        abort_if(
            $this->invitationService->isExpired($invitation),
            410,
            'Invitation has expired.'
        );

        $this->invitationService->touch($invitation);

        $workspace = $invitation->workspace;

        /*
        |--------------------------------------------------------------------------
        | Public Workspace
        |--------------------------------------------------------------------------
        */

        if ($workspace->visibility === 'public') {

            if (
                $this->workspaceMemberService->isMember(
                    $workspace,
                    $request->user()
                )
            ) {
                $request->user()->update([
                    'current_workspace_id' => $workspace->id,
                ]);

                $request->session()->forget(
                    'workspace_invitation_token'
                );

                return redirect()->route('dashboard');
            }

            $this->workspaceMemberService->join(
                $workspace,
                $request->user()
            );

            $request->user()->update([
                'current_workspace_id' => $workspace->id,
            ]);

            $request->session()->forget(
                'workspace_invitation_token'
            );

            return redirect()->route('dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | Private Workspace
        |--------------------------------------------------------------------------
        */

        $request->session()->forget(
            'workspace_invitation_token'
        );

        return redirect()->route(
            'workspace.join-request.create',
            $workspace
        );
    }
}