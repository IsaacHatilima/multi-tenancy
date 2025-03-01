<?php

namespace App\Actions;

class DomainAction
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function update_domain($domain, $request): void
    {
        $domain->update(['domain' => $request->domain]);
    }
}
