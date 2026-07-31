<?php

use App\Http\Controllers\AIController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\Knowledge\KnowledgeFeedbackController;
use App\Http\Controllers\Knowledge\ManualKnowledgeController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;

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

    Route::prefix('onboarding')
        ->name('onboarding.')
        ->group(function () {

            Route::get('/identity', [OnboardingController::class, 'identity'])
                ->name('identity');

            Route::get('/privacy', [OnboardingController::class, 'privacy'])
                ->name('privacy');

            Route::get('/knowledge', [OnboardingController::class, 'knowledge'])
                ->name('knowledge');

            Route::get('/completed', [OnboardingController::class, 'completed'])
                ->name('completed');
        });

    Route::post('/workspaces', [WorkspaceController::class, 'store'])
        ->name('workspaces.store');

    Route::post('/workspaces/{workspace}/switch', [WorkspaceController::class, 'switch'])
        ->name('workspaces.switch');
});

Route::middleware(['auth', 'verified', 'workspace'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::view('/chat', 'chat.index')
        ->name('chat');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::resource('documents', DocumentController::class)
        ->only([
            'index',
            'create',
            'store',
        ]);

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

require __DIR__.'/auth.php';