<?php

namespace App\Http\Controllers;

use App\Actions\TenantAction;
use App\Http\Requests\TenantRequest;
use App\Http\Requests\UpdateTenantRequest;
use App\Models\Tenant;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class TenantController extends Controller
{
    use AuthorizesRequests;

    private TenantAction $tenantAction;

    public function __construct(TenantAction $tenantAction)
    {
        $this->tenantAction = $tenantAction;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Tenant::class);

        return Inertia::render('Tenant/Index', [
            'tenants' => $this->tenantAction->get_tenants($request),
        ]);
    }

    public function store(TenantRequest $request)
    {
        $this->authorize('create', Tenant::class);

        $tenant = $this->tenantAction->create_tenant($request);

        return Redirect::route('tenants.show', $tenant);
    }

    public function show(Request $request, Tenant $tenant)
    {
        $this->authorize('view', $tenant);

        return Inertia::render('Tenant/TenantDetails', [
            'tenant' => $tenant->load('domain'),
            'tenant_users' => Inertia::optional(fn () => $this->tenantAction->get_tenant_users($tenant, $request)),
        ]);
    }

    public function update(UpdateTenantRequest $request, Tenant $tenant)
    {
        $this->authorize('update', $tenant);

        $updatedTenant = $this->tenantAction->update_tenant($request, $tenant);

        return Redirect::route('tenants.show', $updatedTenant);
    }

    public function destroy(Request $request, Tenant $tenant)
    {
        $this->authorize('delete', $tenant);

        $request->validate([
            'current_password' => ['required', 'current_password'],
        ]);

        $this->tenantAction->delete_tenant($tenant);

        return Redirect::route('tenants');
    }
}
