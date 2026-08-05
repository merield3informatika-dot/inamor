<?php

use App\Http\Controllers\AIController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\Knowledge\KnowledgeFeedbackController;
use App\Http\Controllers\Knowledge\ManualKnowledgeController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Workspace\WorkspaceInvitationController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Workspace\WorkspaceJoinRequestController;
use App\Http\Controllers\Workspace\WorkspaceMemberController;
use App\Http\Controllers\Workspace\WorkspaceRoleController;


Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return view('public.landing');
})->name('landing');

Route::get('/get-started', function () {
    return view('auth.choose');
})->name('choose');

Route::post('/ai/chat', [AIController::class, 'chat']);

Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Workspace Join
    |--------------------------------------------------------------------------
    */

    Route::get('/workspaces/{workspace}/join', [
        WorkspaceJoinRequestController::class,
        'create',
    ])->name('workspace.join-request.create');

    Route::post('/workspaces/{workspace}/join', [
        WorkspaceJoinRequestController::class,
        'store',
    ])->name('workspace.join-request.store');

    /*
    |--------------------------------------------------------------------------
    | Onboarding
    |--------------------------------------------------------------------------
    */

    Route::prefix('onboarding')
        ->name('onboarding.')
        ->group(function () {

            Route::get('/identity', [OnboardingController::class, 'identity'])
                ->name('identity');

            Route::post('/identity', [OnboardingController::class, 'storeIdentity'])
                ->name('identity.store');

            Route::get('/privacy', [OnboardingController::class, 'privacy'])
                ->name('privacy');

            Route::post('/privacy', [OnboardingController::class, 'storePrivacy'])
                ->name('privacy.store');

            Route::get('/knowledge', [OnboardingController::class, 'knowledge'])
                ->name('knowledge');

            Route::post('/knowledge', [OnboardingController::class, 'storeKnowledge'])
                ->name('knowledge.store');
        });

    /*
    |--------------------------------------------------------------------------
    | Workspace
    |--------------------------------------------------------------------------
    */

    Route::get('/workspaces/create', [WorkspaceController::class, 'create'])
        ->name('workspaces.create');

    Route::post('/workspaces', [WorkspaceController::class, 'store'])
        ->name('workspaces.store');

    Route::get('/workspaces/create/success', [WorkspaceController::class, 'success'])
        ->name('workspaces.success');

    Route::post('/workspaces/{workspace}/switch', [WorkspaceController::class, 'switch'])
        ->name('workspaces.switch');

    Route::get('/workspace/settings', [WorkspaceController::class, 'edit'])
        ->name('workspace.settings.edit');

    Route::put('/workspace/settings', [WorkspaceController::class, 'update'])
        ->name('workspace.settings.update');

    Route::get('/workspace/settings/danger', [WorkspaceController::class, 'danger'])
        ->name('workspace.settings.danger');

    Route::delete('/workspace', [WorkspaceController::class, 'destroy'])
        ->name('workspace.destroy');
});

Route::middleware(['auth', 'verified', 'workspace'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Workspace Join Request
    |--------------------------------------------------------------------------
    */

    Route::prefix('workspace/join-request')
        ->name('workspace.join-request.')
        ->group(function () {

            Route::get('/', [WorkspaceJoinRequestController::class, 'index'])
                ->name('index');

            Route::get('/pending', [WorkspaceJoinRequestController::class, 'pending'])
                ->name('pending');

            Route::post('/{joinRequest}/approve', [WorkspaceJoinRequestController::class, 'approve'])
                ->name('approve');

            Route::post('/{joinRequest}/reject', [WorkspaceJoinRequestController::class, 'reject'])
                ->name('reject');

                 Route::get('/archived', [
            WorkspaceJoinRequestController::class,
            'archived',
        ])->name('archived');

        Route::post('/{joinRequest}/archive', [
            WorkspaceJoinRequestController::class,
            'archive',
        ])->name('archive');

      Route::post(
    '/{id}/restore',
    [WorkspaceJoinRequestController::class, 'restore']
)->name('restore');
        });

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::view('/chat', 'chat.index')
        ->name('chat');

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Workspace Invitation
    |--------------------------------------------------------------------------
    */

    Route::prefix('workspace/invitation')
        ->name('workspace.invitation.')
        ->group(function () {

            Route::get('/', [WorkspaceInvitationController::class, 'show'])
                ->name('show');

            Route::post('/regenerate', [WorkspaceInvitationController::class, 'regenerate'])
                ->name('regenerate');
        });
/*
|--------------------------------------------------------------------------
| Workspace Members
|--------------------------------------------------------------------------
*/

Route::prefix('workspace/members')
    ->name('workspace.members.')
    ->group(function () {

        Route::get('/', [
            WorkspaceMemberController::class,
            'index',
        ])->name('index');

        Route::post('/{member}/role', [
            WorkspaceMemberController::class,
            'updateRole',
        ])->name('update-role');

        Route::delete('/{member}', [
            WorkspaceMemberController::class,
            'destroy',
        ])->name('destroy');
Route::delete('/workspace/leave', [
    WorkspaceController::class,
    'leave',
])->name('workspace.leave');

    });
    
    /*
|--------------------------------------------------------------------------
| People
|--------------------------------------------------------------------------
*/

Route::get(
    '/people/{username}',
    [ProfileController::class, 'show']
)->name('people.show');

/*
|--------------------------------------------------------------------------
| Workspace Roles
|--------------------------------------------------------------------------
*/

Route::prefix('workspace/roles')
    ->name('workspace.roles.')
    ->group(function () {

        Route::get('/', [
            \App\Http\Controllers\Workspace\WorkspaceRoleController::class,
            'index',
        ])->name('index');

    });
    /*
    |--------------------------------------------------------------------------
    | Documents
    |--------------------------------------------------------------------------
    */

    Route::resource('documents', DocumentController::class)
        ->only([
            'index',
            'create',
            'store',
        ]);

    /*
    |--------------------------------------------------------------------------
    | Knowledge
    |--------------------------------------------------------------------------
    */

    Route::prefix('knowledge')
        ->name('knowledge.')
        ->group(function () {

            Route::get('/feedback', [KnowledgeFeedbackController::class, 'index'])
                ->name('feedback.index');

            Route::get('/feedback/{feedback}', [KnowledgeFeedbackController::class, 'show'])
                ->name('feedback.show');

            Route::get('/manual', [ManualKnowledgeController::class, 'index'])
                ->name('manual.index');

            Route::get('/manual/create', [ManualKnowledgeController::class, 'createManual'])
                ->name('manual.create');

            Route::post('/manual', [ManualKnowledgeController::class, 'storeManual'])
                ->name('manual.store');

            Route::get('/feedback/{feedback}/manual', [ManualKnowledgeController::class, 'create'])
                ->name('manual.feedback.create');

            Route::post('/feedback/{feedback}/manual', [ManualKnowledgeController::class, 'store'])
                ->name('manual.feedback.store');

            Route::get('/manual/{knowledge}', [ManualKnowledgeController::class, 'show'])
                ->name('manual.show');

            Route::get('/manual/{knowledge}/edit', [ManualKnowledgeController::class, 'edit'])
                ->name('manual.edit');

            Route::put('/manual/{knowledge}', [ManualKnowledgeController::class, 'update'])
                ->name('manual.update');

            Route::delete('/manual/{knowledge}', [ManualKnowledgeController::class, 'destroy'])
                ->name('manual.destroy');
        });

    /*
    |--------------------------------------------------------------------------
    | Calendar
    |--------------------------------------------------------------------------
    */

    Route::resource('calendar', CalendarController::class)
        ->only([
            'index',
            'create',
            'store',
            'edit',
            'update',
            'destroy',
        ]);
});

/*
|--------------------------------------------------------------------------
| Public Invitation
|--------------------------------------------------------------------------
*/

Route::get('/invite/{token}', [
    WorkspaceInvitationController::class,
    'accept',
])->name('workspace.invitation.accept');


    

require __DIR__.'/auth.php';