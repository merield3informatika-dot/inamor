<?php

namespace App\Http\Controllers;

use App\Services\WorkspaceService;
use Illuminate\Http\Request;

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
            'name' => ['required', 'string', 'max:255'],
        ]);

        $this->workspaceService->create(
            $request->user(),
            $request->string('name')->toString()
        );

        return redirect()->route('dashboard');
    }
}