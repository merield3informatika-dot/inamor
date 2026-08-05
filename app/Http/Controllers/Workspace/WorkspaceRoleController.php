<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class WorkspaceRoleController extends Controller
{
    public function index(): View
    {
        $roles = [

            [
                'name' => 'Owner',
                'description' => 'Full access to the workspace.',
            ],

            [
                'name' => 'Administrator',
                'description' => 'Can manage members, documents, calendar, and settings.',
            ],

            [
                'name' => 'Member',
                'description' => 'Can access workspace content.',
            ],

            [
                'name' => 'Viewer',
                'description' => 'Read only access.',
            ],

        ];

        return view(
            'workspaces.roles.index',
            compact('roles'),
        );
    }
}