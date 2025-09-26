<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Building;
use App\Models\Flat;
use App\Models\Bill;
use App\Models\Tenant;

class HomeController extends \App\Http\Controllers\Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('tenant.scope');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } else if ($user->isHouseOwner()) {
            return $this->houseOwnerDashboard();
        }
        
        return redirect('/');
    }
    
    private function adminDashboard()
    {
        $stats = [
            'total_house_owners' => \App\Models\User::houseOwners()->count(),
            'total_buildings' => Building::count(),
            'total_flats' => Flat::count(),
            'total_tenants' => Tenant::count(),
            'unpaid_bills' => Bill::unpaid()->count(),
            'total_dues' => Bill::unpaid()->sum('total_amount'),
        ];
        
        $recent_bills = Bill::with(['flat.building', 'billCategory'])
                           ->latest()
                           ->limit(10)
                           ->get();
        
        return view('admin.dashboard', compact('stats', 'recent_bills'));
    }
    
    private function houseOwnerDashboard()
    {
        $user = Auth::user();
        $buildings = $user->buildings()->with(['flats'])->get();
        
        $stats = [
            'total_buildings' => $buildings->count(),
            'total_flats' => $buildings->sum(function($building) {
                return $building->flats->count();
            }),
            'occupied_flats' => $buildings->sum(function($building) {
                return $building->flats->where('is_occupied', true)->count();
            }),
            'unpaid_bills' => Bill::forTenant($user->tenant_id)->unpaid()->count(),
            'total_dues' => Bill::forTenant($user->tenant_id)->unpaid()->sum('total_amount'),
        ];
        
        $recent_bills = Bill::with(['flat', 'billCategory'])
                           ->forTenant($user->tenant_id)
                           ->latest()
                           ->limit(10)
                           ->get();
        
        return view('house_owner.dashboard', compact('stats', 'buildings', 'recent_bills'));
    }
}
