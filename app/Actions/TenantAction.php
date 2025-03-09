<?php

namespace App\Actions;

use App\Models\Tenant;
use App\Models\User;
use Hash;

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
        $query = Tenant::query()->with(['domain', 'createdBy.profile', 'updatedBy.profile'])->orderBy('id', $request->sorting ?: 'desc');

        if ($request->filled('tenant_number')) {
            $query->where('tenant_number', 'like', '%'.strtoupper($request->tenant_number).'%');
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%'.strtoupper($request->name).'%');
        }

        if ($request->filled('domain')) {
            $query->whereHas('domain', function ($q) use ($request) {
                $q->where('domain', 'like', '%'.strtolower($request->domain).'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', strtolower($request->status));
        }

        if ($request->filled('contact_name')) {
            $query->where(function ($query) use ($request) {
                $query->where('contact_first_name', 'like', '%'.ucwords($request->contact_name).'%')
                    ->orWhere('contact_last_name', 'like', '%'.ucwords($request->contact_name).'%');
            });
        }

        $query->orderBy('id', $request->sorting ?: 'desc');

        return $query->paginate(10)->withQueryString();
    }

    public function create_tenant($request)
    {
        $tenant = Tenant::create([
            'name' => strtoupper($request->name),
            'tenant_number' => $this->tenant_number(),
            'address' => ucwords($request->address),
            'city' => ucwords($request->city),
            'state' => ucwords($request->state),
            'country' => ucwords($request->country),
            'zip' => $request->zip,
            'contact_first_name' => ucwords($request->contact_first_name),
            'contact_last_name' => ucwords($request->contact_last_name),
            'contact_email' => strtolower($request->contact_email),
            'contact_phone' => $request->contact_phone,
            'created_by' => auth()->user()->id,
        ]);

        $tenant->domain()->create([
            'domain' => strtolower($request->domain),
        ]);

        $tenant->run(function ($tenant) use ($request) {
            $user = User::create([
                'tenant_id' => $tenant->id,
                'email' => strtolower($request->contact_email),
                'password' => Hash::make('Password1#'),
            ]);

            $user->profile()->create([
                'user_id' => $user->id,
                'first_name' => ucwords($request->contact_first_name),
                'last_name' => ucwords($request->contact_last_name),
            ]);
        });

        return $tenant;
    }

    private function tenant_number(): string
    {
        $tenants = Tenant::count();

        return 'TN-'.str_pad($tenants + 1, 4, '0', STR_PAD_LEFT);
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
            'contact_first_name' => ucwords($request->contact_first_name),
            'contact_last_name' => ucwords($request->contact_last_name),
            'contact_email' => strtolower($request->contact_email),
            'contact_phone' => $request->contact_phone,
            'updated_by' => auth()->user()->id,
        ]);

        return $tenant->refresh();
    }

    public function delete_tenant($tenant): void
    {
        $tenant->delete();
    }
}
