<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class setOrgPrefix
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $org = $request->user()->organization;

        if ($org) {
            $request->route()->forgetParameter('organization');
            $request->route()->setParameter('organization', $org);
            session('org_name', $org->name);
        }
    
        return $next($request);
    }
}
