<?php

namespace App\Actions;

class TenantAction
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
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
