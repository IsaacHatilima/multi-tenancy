<?php

namespace App\Models;

use App\Enums\TaskEscalation;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Task extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = [
        'id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function getStartAttribute($value): string
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getEndAttribute($value): string
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    protected function casts(): array
    {
        return [
            'start' => 'timestamp',
            'end' => 'timestamp',
            'priority' => TaskPriority::class,
            'escalation' => TaskEscalation::class,
            'status' => TaskStatus::class,
        ];
    }
}
