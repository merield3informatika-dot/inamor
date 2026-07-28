<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalendarEvent extends Model
{
    protected $fillable = [
        'workspace_id',
        'created_by',
        'title',
        'description',
        'category',
        'start_at',
        'end_at',
        'location',
        'color',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
        ];
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Label tanggal relatif untuk tampilan dashboard/widget.
     * "Hari Ini" / "Besok" / format singkat seperti "30 Jul".
     */
    protected function dateLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->start_at->isToday()) {
                    return 'Hari Ini';
                }

                if ($this->start_at->isTomorrow()) {
                    return 'Besok';
                }

                return $this->start_at->translatedFormat('d M');
            }
        );
    }

    /**
     * Label rentang waktu. Menampilkan "07:30 - 08:59" jika end_at
     * tersedia, atau "07:30" saja jika tidak.
     */
    protected function timeRangeLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                $start = $this->start_at->format('H:i');

                if (! $this->end_at) {
                    return $start;
                }

                return $start . ' - ' . $this->end_at->format('H:i');
            }
        );
    }
}