<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Services\WorkspaceMemberService;
use App\Services\WorkspaceService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WorkspaceMemberController extends Controller
{
    public function __construct(
        protected WorkspaceMemberService $workspaceMemberService,
        protected WorkspaceService $workspaceService,
    ) {
    }

    /**
     * Display workspace members.
     */
    public function index(Request $request): View
    {
        $members = $this->workspaceMemberService
            ->paginate(
                $request->user(),
            );

        return view('workspaces.members.index', [
            'members' => $members,
            'workspace' => $this->workspaceService->resolveActive(
                $request->user(),
            ),
        ]);
    }

    /**
     * Update member role.
     */
    public function updateRole(
        Request $request,
        int $member,
    ): RedirectResponse {

        $request->validate([
            'role' => [
                'required',
                'in:admin,member,viewer',
            ],
        ]);

        $this->workspaceMemberService->updateRole(
            $request->user(),
            $member,
            $request->role,
        );

        return back()->with(
            'status',
            'Member role updated successfully.',
        );
    }

    public function destroy(
        Request $request,
        int $member,
    ): RedirectResponse {

        $this->workspaceMemberService->remove(
            $request->user(),
            $member,
        );

        return back()->with(
            'status',
            'Member removed successfully.',
        );
    }
}
