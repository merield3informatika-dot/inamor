<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Notification;
use Laravel\Sanctum\HasApiTokens;


#[Fillable(['name',
'email',
'password',

'avatar',

'username',

'bio',

'phone',

'job_title',

'department',

'location',

'google_id',

'current_workspace_id',])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
   use HasApiTokens, HasFactory, Notifiable;

    public function ownedWorkspaces(): HasMany
    {
        return $this->hasMany(Workspace::class, 'owner_id');
    }

    public function workspaceMemberships(): HasMany
    {
        return $this->hasMany(WorkspaceMember::class);
    }

    public function currentWorkspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class, 'current_workspace_id');
    }
    public function joinRequests(): HasMany
{
    return $this->hasMany(
        WorkspaceJoinRequest::class
    );
}
public function createdWorkspaceInvitations(): HasMany
{
    return $this->hasMany(
        WorkspaceInvitation::class,
        'created_by'
    );
}
public function notifications(): HasMany
{
    return $this->hasMany(
        Notification::class,
        'user_id'
    );
}

public function reviewedJoinRequests(): HasMany
{
    return $this->hasMany(
        WorkspaceJoinRequest::class,
        'reviewed_by'
    );
}

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function uploadedDocuments(): HasMany
{
    return $this->hasMany(Document::class, 'uploaded_by');
}
}