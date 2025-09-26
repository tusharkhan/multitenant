<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class TenantScope
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->role === 'admin') {
                return $next($request);
            }
            
            if ($user->role === 'house_owner' && $user->tenant_id) {
                $this->applyTenantScope($user->tenant_id);
            }
        }
        
        return $next($request);
    }
    
    /**
     * Apply tenant scope to all relevant models
     */
    private function applyTenantScope($tenantId)
    {
        // Add global scopes to models
        \App\Models\Building::addGlobalScope('tenant', function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId);
        });
        
        \App\Models\Flat::addGlobalScope('tenant', function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId);
        });
        
        \App\Models\BillCategory::addGlobalScope('tenant', function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId);
        });
        
        \App\Models\Bill::addGlobalScope('tenant', function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId);
        });
    }
}
