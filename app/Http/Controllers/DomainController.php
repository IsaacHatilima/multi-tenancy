<?php

namespace App\Http\Controllers;

use App\Http\Requests\DomainRequest;
use App\Models\Domain;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DomainController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Domain::class);

        return Domain::all();
    }

    public function store(DomainRequest $request)
    {
        $this->authorize('create', Domain::class);

        return Domain::create($request->validated());
    }

    public function show(Domain $domain)
    {
        $this->authorize('view', $domain);

        return $domain;
    }

    public function update(DomainRequest $request, Domain $domain)
    {
        $this->authorize('update', $domain);

        $domain->update($request->validated());

        return $domain;
    }

    public function destroy(Domain $domain)
    {
        $this->authorize('delete', $domain);

        $domain->delete();

        return response()->json();
    }
}
