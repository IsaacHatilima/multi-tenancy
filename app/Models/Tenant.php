<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
            'plan',
        ];
    }

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'data' => 'array',
        ];
    }
}
