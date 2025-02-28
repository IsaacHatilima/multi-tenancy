<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains, HasFactory, HasUuids;

    public static function getCustomColumns(): array
    {
        // Columns that won't be stored in data json column
        return [
            'id',
            'created_at',
            'updated_at',
            'tenancy_db_name',
            'status',
            'name',
            'address',
            'city',
            'state',
            'country',
            'zip',
            'contact_email',
            'contact_phone',
            'contact_name',
            'tenant_number',
            'slug',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($tenant) {
            $tenant->slug = Str::slug($tenant->name);
        });

        static::updating(function ($tenant) {
            $tenant->slug = Str::slug($tenant->name);
        });
    }

    public function getCreatedAtAttribute($value): string
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute($value): string
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function getStatusAttribute($value): string
    {
        return strtoupper($value);
    }

    public function domain(): HasOne
    {
        return $this->hasOne(Domain::class);
    }

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'data' => 'array',
            'created_at' => 'datetime',
        ];
    }
}
