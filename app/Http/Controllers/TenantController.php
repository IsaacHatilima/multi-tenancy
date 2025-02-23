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

        // Paginate results and keep query string (preserves search filters)
        return Inertia::render('Tenant/Index', [
            'tenants' => $query->paginate(10)->withQueryString(),
        ]);
    }

    public function store(TenantRequest $request)
    {
        $this->authorize('create', Tenant::class);

        return Tenant::create($request->validated());
    }

    public function show($slug)
    {
        $tenant = Tenant::where('slug', $slug)->with('domain')->firstOrFail();

        return Inertia::render('Tenant/TenantDetail', [
            'tenant' => $tenant,
        ]);
    }

    public function update(UpdateTenantRequest $request, Tenant $tenant)
    {
        $this->authorize('update', $tenant);

        $updatedTenant = $this->tenantAction->update_tenant($request, $tenant);

        return Redirect::route('tenants.update', $updatedTenant->slug);
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
