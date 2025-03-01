<?php

namespace App\Http\Controllers;

use App\Actions\DomainAction;
use App\Http\Requests\DomainRequest;
use App\Models\Domain;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Redirect;

class DomainController extends Controller
{
    use AuthorizesRequests;

    private DomainAction $domainAction;

    public function __construct(DomainAction $domainAction)
    {
        $this->domainAction = $domainAction;
    }

    public function update(DomainRequest $request, Domain $domain)
    {
        $this->authorize('update', $domain);

        $this->domainAction->update_domain($domain, $request);

        return Redirect::back();
    }
}
