<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Services\WorkspaceService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WorkspaceController extends Controller
{
    public function __construct(
        protected WorkspaceService $workspaceService
    ) {}

    public function create()
    {
        return view('workspaces.create');
    }

    public function store(Request $request)
    {
        $request->validate([
           'name' => [
    'required',
    'string',
    'max:255',
    Rule::unique('workspaces', 'name'),
],
        ]);

        $this->workspaceService->create(
            $request->user(),
            $request->string('name')->toString()
        );

        return redirect()->route('dashboard');
    }

    public function switch(Request $request, Workspace $workspace)
    {
        $this->workspaceService->switchTo(
            $request->user(),
            $workspace
        );

        return redirect()->route('dashboard');
    }
}