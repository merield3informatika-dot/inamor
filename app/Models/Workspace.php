<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Workspace extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'owner_id',
        'name',
        'slug',
        'logo',
        'description',
        'visibility',
    ];

    /*
    |--------------------------------------------------------------------------
    | Owner
    |--------------------------------------------------------------------------
    */

    public function owner(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'owner_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Members
    |--------------------------------------------------------------------------
    */

    public function members(): HasMany
    {
        return $this->hasMany(
            WorkspaceMember::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Documents
    |--------------------------------------------------------------------------
    */

    public function documents(): HasMany
    {
        return $this->hasMany(
            Document::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Calendar
    |--------------------------------------------------------------------------
    */

    public function calendarEvents(): HasMany
    {
        return $this->hasMany(
            CalendarEvent::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Manual Knowledge
    |--------------------------------------------------------------------------
    */

    public function manualKnowledges(): HasMany
    {
        return $this->hasMany(
            ManualKnowledge::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Knowledge Feedback
    |--------------------------------------------------------------------------
    */

    public function knowledgeFeedbacks(): HasMany
    {
        return $this->hasMany(
            KnowledgeFeedback::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Join Requests
    |--------------------------------------------------------------------------
    */

    public function joinRequests(): HasMany
    {
        return $this->hasMany(
            WorkspaceJoinRequest::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Invitation
    |--------------------------------------------------------------------------
    */

    public function invitation(): HasOne
    {
        return $this->hasOne(
            WorkspaceInvitation::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Announcements
    |--------------------------------------------------------------------------
    */

    public function announcements(): HasMany
    {
        return $this->hasMany(
            Announcement::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Chat Conversations
    |--------------------------------------------------------------------------
    */

    public function chatConversations(): HasMany
    {
        return $this->hasMany(
            WorkspaceConversation::class
        );
    }
}