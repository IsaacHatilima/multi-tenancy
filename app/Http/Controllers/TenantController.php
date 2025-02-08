<?php

namespace App\Http\Controllers;

use App\Http\Requests\TenantRequest;
use App\Models\Tenant;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class TenantController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
//        $this->authorize('viewAny', Tenant::class);

        return Inertia::render('Tenant/Index',);
    }

    public function store(TenantRequest $request)
    {
        $this->authorize('create', Tenant::class);

        return Tenant::create($request->validated());
    }

    public function show(Tenant $tenant)
    {
        $this->authorize('view', $tenant);

        return $tenant;
    }

    public function update(TenantRequest $request, Tenant $tenant)
    {
        $this->authorize('update', $tenant);

        $tenant->update($request->validated());

        return $tenant;
    }

    public function destroy(Tenant $tenant)
    {
        $this->authorize('delete', $tenant);

        $tenant->delete();

        return response()->json();
    }
}
