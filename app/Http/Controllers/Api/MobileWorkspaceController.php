<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use App\Models\WorkspaceJoinRequest;
use App\Models\WorkspaceMember;
use App\Services\WorkspaceInvitationService;
use App\Services\WorkspaceJoinRequestService;
use App\Services\WorkspaceMemberService;
use App\Services\WorkspaceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class MobileWorkspaceController extends Controller
{
    public function __construct(
        private readonly WorkspaceInvitationService $invitationService,
        private readonly WorkspaceJoinRequestService $joinRequestService,
        private readonly WorkspaceMemberService $memberService,
        private readonly WorkspaceService $workspaceService,
    ) {
    }

    /* =========================================================
       WORKSPACE STATE
    ========================================================= */

    public function state(
        Request $request,
    ): JsonResponse {
        $user = $request->user();

        if ($user->current_workspace_id) {
            $workspace = $user->currentWorkspace;

            if (
                $workspace &&
                $this->memberService->isMember(
                    $workspace,
                    $user,
                )
            ) {
                return response()->json([
                    'state' => 'active',

                    'workspace' => [
                        'id' => $workspace->id,
                        'name' => $workspace->name,
                        'visibility' => $workspace->visibility,
                        'logo_url' => $this->logoUrl($workspace),
                    ],
                ]);
            }
        }

        $pending = WorkspaceJoinRequest::query()
            ->with('workspace')
            ->where(
                'user_id',
                $user->id,
            )
            ->where(
                'status',
                'pending',
            )
            ->latest()
            ->first();

        if ($pending) {
            return response()->json([
                'state' => 'pending',

                'workspace' => [
                    'id' => $pending->workspace->id,
                    'name' => $pending->workspace->name,
                    'visibility' => $pending->workspace->visibility,
                    'logo_url' => $this->logoUrl(
                        $pending->workspace,
                    ),
                ],

                'join_request' => [
                    'id' => $pending->id,
                    'status' => $pending->status,
                    'created_at' =>
                        $pending->created_at?->toISOString(),
                ],
            ]);
        }

        return response()->json([
            'state' => 'none',
            'workspace' => null,
            'join_request' => null,
        ]);
    }


    /* =========================================================
       MY WORKSPACES
    ========================================================= */

    public function workspaces(
        Request $request,
    ): JsonResponse {
        $user = $request->user();

        $memberships = WorkspaceMember::query()
            ->with('workspace')
            ->where(
                'user_id',
                $user->id,
            )
            ->latest('id')
            ->get();

        $workspaces = $memberships
            ->filter(
                fn (WorkspaceMember $membership) =>
                    $membership->workspace !== null
            )
            ->map(
                function (
                    WorkspaceMember $membership
                ) use ($user) {
                    $workspace =
                        $membership->workspace;

                    return [
                        'id' => $workspace->id,

                        'name' =>
                            $workspace->name,

                        'description' =>
                            $workspace->description,

                        'visibility' =>
                            $workspace->visibility,

                        'logo_url' =>
                            $this->logoUrl(
                                $workspace,
                            ),

                        'role' =>
                            $membership->role,

                        'is_current' =>
                            $workspace->id ===
                            $user->current_workspace_id,
                    ];
                },
            )
            ->values();

        return response()->json([
            'current_workspace_id' =>
                $user->current_workspace_id,

            'workspaces' =>
                $workspaces,
        ]);
    }


    /* =========================================================
       SWITCH ACTIVE WORKSPACE
    ========================================================= */

    public function switchWorkspace(
        Request $request,
        int $workspace,
    ): JsonResponse {
        $user = $request->user();

        $workspaceModel =
            Workspace::query()
                ->find($workspace);

        if (! $workspaceModel) {
            return response()->json([
                'message' =>
                    'Workspace tidak ditemukan.',
            ], 404);
        }

        try {
            /*
             * WorkspaceService melakukan pengecekan
             * membership sebelum switch.
             */
            $this->workspaceService->switchTo(
                $user,
                $workspaceModel,
            );
        } catch (HttpException $e) {
            return response()->json([
                'message' =>
                    $e->getMessage(),
            ], $e->getStatusCode());
        }

        $user->refresh();

        return response()->json([
            'message' =>
                'Workspace berhasil diganti.',

            'current_workspace_id' =>
                $user->current_workspace_id,

            'workspace' => [
                'id' =>
                    $workspaceModel->id,

                'name' =>
                    $workspaceModel->name,

                'description' =>
                    $workspaceModel->description,

                'visibility' =>
                    $workspaceModel->visibility,

                'logo_url' =>
                    $this->logoUrl(
                        $workspaceModel,
                    ),
            ],
        ]);
    }


    /* =========================================================
       INVITATION DETAIL
    ========================================================= */

    public function invitation(
        string $token,
    ): JsonResponse {
        $invitation =
            $this->invitationService
                ->findByToken($token);

        if (! $invitation) {
            return response()->json([
                'message' =>
                    'Invitation tidak ditemukan.',
            ], 404);
        }

        if (
            $this->invitationService
                ->isExpired($invitation)
        ) {
            return response()->json([
                'message' =>
                    'Invitation sudah kedaluwarsa.',
            ], 410);
        }

        $workspace =
            $invitation->workspace;

        return response()->json([
            'invitation' => [
                'token' =>
                    $invitation->token,

                'status' =>
                    $invitation->status,

                'expires_at' =>
                    $invitation
                        ->expires_at
                        ?->toISOString(),
            ],

            'workspace' => [
                'id' =>
                    $workspace->id,

                'name' =>
                    $workspace->name,

                'visibility' =>
                    $workspace->visibility,

                'logo_url' =>
                    $this->logoUrl(
                        $workspace,
                    ),
            ],
        ]);
    }


    /* =========================================================
       JOIN WORKSPACE
    ========================================================= */

    public function join(
        Request $request,
        string $token,
    ): JsonResponse {
        $invitation =
            $this->invitationService
                ->findByToken($token);

        if (! $invitation) {
            return response()->json([
                'message' =>
                    'Invitation tidak ditemukan.',
            ], 404);
        }

        if (
            $this->invitationService
                ->isExpired($invitation)
        ) {
            return response()->json([
                'message' =>
                    'Invitation sudah kedaluwarsa.',
            ], 410);
        }

        $workspace =
            $invitation->workspace;

        $user =
            $request->user();


        /* =====================================================
           ALREADY MEMBER
        ===================================================== */

        if (
            $this->memberService->isMember(
                $workspace,
                $user,
            )
        ) {
            $user->update([
                'current_workspace_id' =>
                    $workspace->id,
            ]);

            return response()->json([
                'state' => 'active',

                'message' =>
                    'Workspace sudah menjadi workspace aktif.',

                'workspace' => [
                    'id' =>
                        $workspace->id,

                    'name' =>
                        $workspace->name,
                ],
            ]);
        }


        /* =====================================================
           PUBLIC WORKSPACE
        ===================================================== */

        if (
            $workspace->visibility ===
            'public'
        ) {
            DB::transaction(
                function () use (
                    $workspace,
                    $user,
                    $invitation,
                ) {
                    $this->memberService->join(
                        $workspace,
                        $user,
                    );

                    $user->update([
                        'current_workspace_id' =>
                            $workspace->id,
                    ]);

                    $this->invitationService->touch(
                        $invitation,
                    );
                },
            );

            return response()->json([
                'state' => 'active',

                'message' =>
                    'Berhasil bergabung ke workspace.',

                'workspace' => [
                    'id' =>
                        $workspace->id,

                    'name' =>
                        $workspace->name,
                ],
            ]);
        }


        /* =====================================================
           PRIVATE WORKSPACE
        ===================================================== */

        $existingRequest =
            WorkspaceJoinRequest::query()
                ->where(
                    'workspace_id',
                    $workspace->id,
                )
                ->where(
                    'user_id',
                    $user->id,
                )
                ->where(
                    'status',
                    'pending',
                )
                ->first();

        if ($existingRequest) {
            return response()->json([
                'state' =>
                    'pending',

                'message' =>
                    'Kamu sudah memiliki request yang sedang menunggu persetujuan.',

                'workspace' => [
                    'id' =>
                        $workspace->id,

                    'name' =>
                        $workspace->name,
                ],

                'join_request' => [
                    'id' =>
                        $existingRequest->id,

                    'status' =>
                        $existingRequest->status,
                ],
            ]);
        }


        return response()->json([
            'state' =>
                'request_required',

            'message' =>
                'Workspace ini private dan membutuhkan persetujuan admin.',

            'workspace' => [
                'id' =>
                    $workspace->id,

                'name' =>
                    $workspace->name,

                'visibility' =>
                    $workspace->visibility,

                'logo_url' =>
                    $this->logoUrl(
                        $workspace,
                    ),
            ],
        ]);
    }


    /* =========================================================
       REQUEST ACCESS
    ========================================================= */

    public function requestAccess(
        Request $request,
        string $token,
    ): JsonResponse {
        /*
         * Email sengaja tidak dipakai.
         *
         * Request Access membutuhkan:
         * - full_name
         * - identity_card
         * - selfie_with_identity_card
         */

        $validated =
            $request->validate([
                'full_name' => [
                    'required',
                    'string',
                    'max:255',
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


        /* =====================================================
           FIND INVITATION
        ===================================================== */

        $invitation =
            $this->invitationService
                ->findByToken($token);

        if (! $invitation) {
            return response()->json([
                'message' =>
                    'Invitation tidak ditemukan.',
            ], 404);
        }


        /* =====================================================
           CHECK EXPIRATION
        ===================================================== */

        if (
            $this->invitationService
                ->isExpired($invitation)
        ) {
            return response()->json([
                'message' =>
                    'Invitation sudah kedaluwarsa.',
            ], 410);
        }


        $workspace =
            $invitation->workspace;

        $user =
            $request->user();


        /* =====================================================
           MUST BE PRIVATE
        ===================================================== */

        if (
            $workspace->visibility !==
            'private'
        ) {
            return response()->json([
                'message' =>
                    'Workspace ini tidak membutuhkan join request.',
            ], 422);
        }


        /* =====================================================
           ALREADY MEMBER
        ===================================================== */

        if (
            $this->memberService->isMember(
                $workspace,
                $user,
            )
        ) {
            $user->update([
                'current_workspace_id' =>
                    $workspace->id,
            ]);

            return response()->json([
                'state' =>
                    'active',

                'message' =>
                    'Kamu sudah menjadi member workspace.',
            ]);
        }


        /* =====================================================
           EXISTING PENDING REQUEST
        ===================================================== */

        $existingRequest =
            WorkspaceJoinRequest::query()
                ->where(
                    'workspace_id',
                    $workspace->id,
                )
                ->where(
                    'user_id',
                    $user->id,
                )
                ->where(
                    'status',
                    'pending',
                )
                ->first();

        if ($existingRequest) {
            return response()->json([
                'state' =>
                    'pending',

                'message' =>
                    'Request kamu sudah menunggu persetujuan admin.',

                'join_request' => [
                    'id' =>
                        $existingRequest->id,

                    'status' =>
                        $existingRequest->status,
                ],
            ]);
        }


        /* =====================================================
           STORE IDENTITY CARD
        ===================================================== */

        $identityCardPath =
            $request
                ->file('identity_card')
                ->store(
                    'join-requests/identity',
                    'public',
                );


        /* =====================================================
           STORE SELFIE
        ===================================================== */

        $selfiePath =
            $request
                ->file(
                    'selfie_with_identity_card',
                )
                ->store(
                    'join-requests/selfie',
                    'public',
                );


        /* =====================================================
           CREATE JOIN REQUEST
        ===================================================== */

        $joinRequest =
            $this->joinRequestService->submit(
                $workspace,
                $user,
                [
                    'full_name' =>
                        $validated[
                            'full_name'
                        ],

                    'identity_card_path' =>
                        $identityCardPath,

                    'selfie_with_identity_card_path' =>
                        $selfiePath,
                ],
            );


        /* =====================================================
           TOUCH INVITATION
        ===================================================== */

        $this->invitationService->touch(
            $invitation,
        );


        /* =====================================================
           RESPONSE
        ===================================================== */

        return response()->json([
            'state' =>
                'pending',

            'message' =>
                'Join request berhasil dikirim.',

            'workspace' => [
                'id' =>
                    $workspace->id,

                'name' =>
                    $workspace->name,
            ],

            'join_request' => [
                'id' =>
                    $joinRequest->id,

                'status' =>
                    $joinRequest->status,
            ],
        ], 201);
    }


    /* =========================================================
       JOIN REQUEST STATUS
    ========================================================= */

    public function joinRequestStatus(
        Request $request,
    ): JsonResponse {
        $joinRequest =
            WorkspaceJoinRequest::query()
                ->with('workspace')
                ->where(
                    'user_id',
                    $request
                        ->user()
                        ->id,
                )
                ->latest()
                ->first();

        if (! $joinRequest) {
            return response()->json([
                'state' =>
                    'none',

                'join_request' =>
                    null,
            ]);
        }


        /* =====================================================
           APPROVED
        ===================================================== */

        if (
            $joinRequest->status ===
                'approved' &&
            $joinRequest->workspace
        ) {
            $request
                ->user()
                ->update([
                    'current_workspace_id' =>
                        $joinRequest
                            ->workspace_id,
                ]);

            return response()->json([
                'state' =>
                    'active',

                'join_request' => [
                    'id' =>
                        $joinRequest->id,

                    'status' =>
                        $joinRequest->status,
                ],

                'workspace' => [
                    'id' =>
                        $joinRequest
                            ->workspace
                            ->id,

                    'name' =>
                        $joinRequest
                            ->workspace
                            ->name,
                ],
            ]);
        }


        /* =====================================================
           OTHER STATUS
        ===================================================== */

        return response()->json([
            'state' =>
                $joinRequest->status,

            'join_request' => [
                'id' =>
                    $joinRequest->id,

                'status' =>
                    $joinRequest->status,

                'rejection_reason' =>
                    $joinRequest
                        ->rejection_reason,

                'reviewed_at' =>
                    $joinRequest
                        ->reviewed_at
                        ?->toISOString(),
            ],

            'workspace' => [
                'id' =>
                    $joinRequest
                        ->workspace
                        ->id,

                'name' =>
                    $joinRequest
                        ->workspace
                        ->name,
            ],
        ]);
    }


    /* =========================================================
       HELPERS
    ========================================================= */

    private function logoUrl(
        Workspace $workspace,
    ): ?string {
        return $workspace->logo
            ? asset(
                'storage/' .
                $workspace->logo,
            )
            : null;
    }
    /* =========================================================
   WORKSPACE MEMBERS
========================================================= */

public function members(
    Request $request,
): JsonResponse {
    $members = $this->memberService->paginate(
        $request->user(),
    );

    return response()->json([
        'members' => $members
            ->getCollection()
            ->map(function ($member) {
                return [
                    'id' => $member->id,

                    'role' => $member->role,

                    'user' => [
                        'id' =>
                            $member->user?->id,

                        'name' =>
                            $member->user?->name,

                        'username' =>
                            $member->user?->username,

                        'avatar_url' =>
                            $member->user?->avatar
                                ? asset(
                                    'storage/' .
                                    $member->user->avatar
                                )
                                : null,
                    ],
                ];
            })
            ->values(),

        'pagination' => [
            'current_page' =>
                $members->currentPage(),

            'last_page' =>
                $members->lastPage(),

            'per_page' =>
                $members->perPage(),

            'total' =>
                $members->total(),
        ],
    ]);
}
public function leave(Request $request): JsonResponse
{
    $user = $request->user();

    $workspaceId = $user->current_workspace_id;

    if (! $workspaceId) {
        return response()->json([
            'message' => 'Tidak ada workspace aktif.',
        ], 422);
    }

    $membership = OrganizationMember::query()
        ->where('user_id', $user->id)
        ->where('organization_id', $workspaceId)
        ->first();

    if (! $membership) {
        return response()->json([
            'message' => 'Kamu bukan anggota workspace ini.',
        ], 403);
    }

    /*
    |--------------------------------------------------------------------------
    | Owner tidak boleh keluar begitu saja
    |--------------------------------------------------------------------------
    */

    if ($membership->role === 'owner') {
        return response()->json([
            'message' => 'Owner tidak dapat keluar dari workspace. Transfer kepemilikan terlebih dahulu.',
        ], 422);
    }

    /*
    |--------------------------------------------------------------------------
    | Remove membership
    |--------------------------------------------------------------------------
    */

    $membership->delete();

    /*
    |--------------------------------------------------------------------------
    | Clear current workspace
    |--------------------------------------------------------------------------
    */

    $user->forceFill([
        'current_workspace_id' => null,
    ])->save();

    return response()->json([
        'message' => 'Berhasil keluar dari workspace.',
        'current_workspace_id' => null,
    ]);
}
}