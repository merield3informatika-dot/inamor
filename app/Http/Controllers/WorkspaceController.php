<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Services\WorkspaceService;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    public function __construct(
        protected WorkspaceService $workspaceService
    ) {
    }

    public function create()
    {
        return view('workspaces.create.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'visibility' => ['required', 'in:public,private'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store(
                'workspace-logos',
                'public'
            );
        }

        $this->workspaceService->create(
            $request->user(),
            $validated
        );

        return redirect()->route('workspaces.success');
    }

    public function success(Request $request)
    {
        $workspace = $this->workspaceService->resolveActive(
            $request->user()
        );

        return view('workspaces.create.success', [
            'workspace' => $workspace,
        ]);
    }

    public function switch(Request $request, Workspace $workspace)
    {
        $this->workspaceService->switchTo(
            $request->user(),
            $workspace
        );

        return redirect()->route('dashboard');
    }

    public function edit(Request $request)
    {
        $workspace = $this->workspaceService->resolveActive(
            $request->user()
        );

        return view('workspaces.settings.general', [
            'workspace' => $workspace,
        ]);
    }

    public function update(Request $request)
    {
        $workspace = $this->workspaceService->resolveActive(
            $request->user()
        );

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'visibility' => ['required', 'in:public,private'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store(
                'workspace-logos',
                'public'
            );
        } else {
            unset($validated['logo']);
        }

        $this->workspaceService->update(
            $workspace,
            $validated
        );

        return redirect()
            ->route('workspace.settings.edit')
            ->with('status', 'Workspace berhasil diperbarui.');
    }

    public function danger(Request $request)
    {
        $workspace = $this->workspaceService->resolveActive(
            $request->user()
        );

        return view('workspaces.settings.danger', [
            'workspace' => $workspace,
        ]);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $hasFallbackWorkspace = $this->workspaceService->destroy(
            $request->user(),
            $request->string('password')->toString()
        );

        return $hasFallbackWorkspace
            ? redirect()
                ->route('dashboard')
                ->with('status', 'Workspace berhasil dihapus.')
            : redirect()
                ->route('onboarding.identity')
                ->with('status', 'Workspace berhasil dihapus.');
    }
    public function leave(Request $request): RedirectResponse
{
    $this->workspaceService->leave(
        $request->user()
    );

    return redirect()
        ->route('dashboard')
        ->with(
            'status',
            'You have left the workspace.'
        );
}
}