<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use App\Models\WorkspaceJoinRequest;
use App\Services\WorkspaceJoinRequestService;
use App\Services\WorkspaceMemberService;
use App\Services\WorkspaceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkspaceJoinRequestController extends Controller
{
    public function __construct(
        protected WorkspaceJoinRequestService $joinRequestService,
        protected WorkspaceMemberService $memberService,
        protected WorkspaceService $workspaceService,
    ) {
    }

    /**
     * Join request page.
     */
    public function create(
        Workspace $workspace,
    ): View {

        return view(
            'workspaces.join-request.create',
            [
                'workspace' => $workspace,
            ]
        );
    }

    /**
     * Submit join request.
     */
    public function store(
        Request $request,
        Workspace $workspace,
    ): RedirectResponse {

        $validated = $request->validate([

            'full_name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
            ],

            'identity_card' => [
                'required',
                'image',
                'max:5120',
            ],

            'selfie_with_identity_card' => [
                'required',
                'image',
                'max:5120',
            ],

        ]);

        $validated['identity_card_path'] =
            $request->file('identity_card')
                ->store('join-requests/identity', 'public');

        $validated['selfie_with_identity_card_path'] =
            $request->file('selfie_with_identity_card')
                ->store('join-requests/selfie', 'public');

        $this->joinRequestService->submit(
            $workspace,
            $request->user(),
            $validated,
        );

        return redirect()->route(
            'workspace.join-request.pending'
        );
    }

    /**
     * Pending page.
     */
    public function pending(): View
    {
        return view(
            'workspaces.join-request.pending'
        );
    }

    /**
     * Admin list.
     */
    public function index(
        Request $request,
    ): View {

        $workspace = $this->workspaceService
            ->resolveActive(
                $request->user()
            );

      $joinRequests = $workspace
    ->joinRequests()
    ->latest()
    ->paginate(15);

return view(
    'workspaces.join-request.index',
    compact(
        'workspace',
        'joinRequests'
    )
);
    }

    /**
     * Approve request.
     */
    public function approve(
        Request $request,
        WorkspaceJoinRequest $joinRequest,
    ): RedirectResponse {

        $this->memberService->join(
            $joinRequest->workspace,
            $joinRequest->user,
        );

        $this->joinRequestService->approve(
            $joinRequest,
            $request->user(),
        );

        return back()->with(
            'status',
            'Join request approved.'
        );
    }

    /**
     * Reject request.
     */
    public function reject(
        Request $request,
        WorkspaceJoinRequest $joinRequest,
    ): RedirectResponse {

        $validated = $request->validate([
            'reason' => [
                'required',
                'string',
                'max:500',
            ],
        ]);

        $this->joinRequestService->reject(
            $joinRequest,
            $request->user(),
            $validated['reason'],
        );

        return back()->with(
            'status',
            'Join request rejected.'
        );
    }
    /**
 * Archive request.
 */
public function archive(
    WorkspaceJoinRequest $joinRequest,
): RedirectResponse {

    $this->joinRequestService->archive(
        $joinRequest,
    );

    return back()->with(
        'status',
        'Join request archived successfully.',
    );
}
/**
 * Archived requests.
 */
public function archived(
    Request $request,
): View {

    $workspace = $this->workspaceService
        ->resolveActive(
            $request->user(),
        );

    $joinRequests = $this->joinRequestService
        ->paginateArchived(
            $workspace->id,
        );

    return view(
        'workspaces.join-request.archived',
        compact(
            'workspace',
            'joinRequests',
        ),
    );
}

public function restore(
    int $id,
): RedirectResponse {

    $this->joinRequestService->restore($id);

    return back()->with(
        'status',
        'Join request restored.',
    );
}
}