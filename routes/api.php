<?php

use App\Http\Controllers\AIController;
use App\Http\Controllers\Api\MobileAccountController;
use App\Http\Controllers\Api\MobileAnnouncementController;
use App\Http\Controllers\Api\MobileAuthController;
use App\Http\Controllers\Api\MobileCalendarController;
use App\Http\Controllers\Api\MobileChatController;
use App\Http\Controllers\Api\MobileHomeController;
use App\Http\Controllers\Api\MobileNotificationController;
use App\Http\Controllers\Api\MobileWorkspaceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MobileDocumentController;

/*
|--------------------------------------------------------------------------
| MOBILE PUBLIC
|--------------------------------------------------------------------------
*/

Route::post('/mobile/register', [
    MobileAuthController::class,
    'register',
]);

Route::post('/mobile/login', [
    MobileAuthController::class,
    'login',
]);


/*
|--------------------------------------------------------------------------
| MOBILE AUTHENTICATED
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | AUTH
    |--------------------------------------------------------------------------
    */

    Route::post('/mobile/logout', [
        MobileAuthController::class,
        'logout',
    ]);

    Route::get('/mobile/user', [
        MobileAuthController::class,
        'user',
    ]);


    /*
    |--------------------------------------------------------------------------
    | WORKSPACE
    |--------------------------------------------------------------------------
    */

    // Current workspace state
    Route::get('/mobile/workspace/state', [
        MobileWorkspaceController::class,
        'state',
    ]);

    // All workspaces owned/joined by user
    Route::get('/mobile/workspaces', [
        MobileWorkspaceController::class,
        'workspaces',
    ]);

    // Switch active workspace
    Route::post(
        '/mobile/workspaces/{workspace}/switch',
        [
            MobileWorkspaceController::class,
            'switchWorkspace',
        ],
    );

    // Members of current workspace
    Route::get('/mobile/workspace/members', [
        MobileWorkspaceController::class,
        'members',
    ]);


    /*
    |--------------------------------------------------------------------------
    | WORKSPACE INVITATION
    |--------------------------------------------------------------------------
    */

    Route::get('/mobile/invitations/{token}', [
        MobileWorkspaceController::class,
        'invitation',
    ]);

    Route::post(
        '/mobile/invitations/{token}/join',
        [
            MobileWorkspaceController::class,
            'join',
        ],
    );

    Route::post(
        '/mobile/invitations/{token}/request',
        [
            MobileWorkspaceController::class,
            'requestAccess',
        ],
    );


    /*
    |--------------------------------------------------------------------------
    | JOIN REQUEST
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/mobile/workspace/join-request/status',
        [
            MobileWorkspaceController::class,
            'joinRequestStatus',
        ],
    );


    /*
    |--------------------------------------------------------------------------
    | HOME
    |--------------------------------------------------------------------------
    */

    Route::get('/mobile/home', [
        MobileHomeController::class,
        'index',
    ]);


    /*
    |--------------------------------------------------------------------------
    | CALENDAR
    |--------------------------------------------------------------------------
    */

    Route::get('/mobile/calendar', [
        MobileCalendarController::class,
        'index',
    ]);
  /*
    |--------------------------------------------------------------------------
    | documents
    |--------------------------------------------------------------------------
    */
Route::get('/mobile/documents', [
    MobileDocumentController::class,
    'index',
]);

Route::get('/mobile/documents/{document}', [
    MobileDocumentController::class,
    'show',
]);

Route::get('/mobile/documents/{document}/file', [
    MobileDocumentController::class,
    'file',
]);
    /*
    |--------------------------------------------------------------------------
    | ANNOUNCEMENTS
    |--------------------------------------------------------------------------
    */

    Route::get('/mobile/announcements', [
        MobileAnnouncementController::class,
        'index',
    ]);

    Route::get(
        '/mobile/announcements/{announcement}',
        [
            MobileAnnouncementController::class,
            'show',
        ],
    );


    /*
    |--------------------------------------------------------------------------
    | NOTIFICATIONS
    |--------------------------------------------------------------------------
    */

    Route::get('/mobile/notifications', [
        MobileNotificationController::class,
        'index',
    ]);

    Route::patch(
        '/mobile/notifications/{notification}/read',
        [
            MobileNotificationController::class,
            'markAsRead',
        ],
    );

    Route::patch(
        '/mobile/notifications/read-all',
        [
            MobileNotificationController::class,
            'markAllAsRead',
        ],
    );


    /*
    |--------------------------------------------------------------------------
    | AI
    |--------------------------------------------------------------------------
    */

    Route::post('/mobile/ai/chat', [
        AIController::class,
        'chat',
    ]);


    /*
    |--------------------------------------------------------------------------
    | CHAT
    |--------------------------------------------------------------------------
    */

    Route::get('/mobile/chat', [
        MobileChatController::class,
        'index',
    ]);

    Route::post('/mobile/chat/messages', [
        MobileChatController::class,
        'sendMessage',
    ]);


    /*
    |--------------------------------------------------------------------------
    | ACCOUNT
    |--------------------------------------------------------------------------
    */

    Route::get('/mobile/account', [
        MobileAccountController::class,
        'show',
    ]);

    Route::post('/mobile/account', [
        MobileAccountController::class,
        'update',
    ]);
});