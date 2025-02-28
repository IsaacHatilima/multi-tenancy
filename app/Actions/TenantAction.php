<?php

namespace App\Actions;

use App\Models\Tenant;

class TenantAction
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function get_tenants($request)
    {

        $query = Tenant::query()->with('domain');

        if ($request->filled('tenant_number')) {
            $query->where('tenant_number', 'ILIKE', '%'.$request->tenant_number.'%');
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%'.$request->name.'%');
        }

        if ($request->filled('domain')) {
            $query->whereHas('domain', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->domain.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('contact_name')) {
            $query->where('contact_name', 'like', '%'.$request->contact_name.'%');
        }

        $query->orderBy('created_at', $request->sorting === 'ascending' ? 'asc' : 'desc');

        return $query->paginate(10)->withQueryString();
    }

    public function update_tenant($request, $tenant)
    {
        $tenant->update([
            'name' => strtoupper($request->name),
            'status' => $request->status,
            'address' => ucwords($request->address),
            'city' => ucwords($request->city),
            'state' => ucwords($request->state),
            'country' => ucwords($request->country),
            'zip' => $request->zip,
            'contact_name' => ucwords($request->contact_name),
            'contact_email' => strtolower($request->contact_email),
            'contact_phone' => $request->contact_phone,
        ]);

        return $tenant->refresh();
    }

    public function delete_tenant($tenant): void
    {
        $tenant->delete();
    }
}
