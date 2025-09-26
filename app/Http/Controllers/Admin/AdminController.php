<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Building;
use App\Models\Flat;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403, 'Access denied. Admin only.');
            }
            return $next($request);
        });
    }

    // House Owners Management
    public function houseOwners()
    {
        $houseOwners = User::houseOwners()->with(['buildings'])->paginate(15);
        return view('admin.house_owners.index', compact('houseOwners'));
    }

    public function createHouseOwner()
    {
        return view('admin.house_owners.create');
    }

    public function storeHouseOwner(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $tenantId = $this->generateTenantId();

        $houseOwner = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'role' => 'house_owner',
            'tenant_id' => $tenantId,
            'is_active' => true,
        ]);

        return redirect()->route('admin.house_owners.index')
                        ->with('success', 'House Owner created successfully!');
    }

    public function editHouseOwner(User $houseOwner)
    {
        $houseOwner->load('buildings');
        
        return view('admin.house_owners.edit', compact('houseOwner'));
    }

    public function updateHouseOwner(Request $request, User $houseOwner)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $houseOwner->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $houseOwner->update($validated);

        return redirect()->route('admin.house_owners.index')
                        ->with('success', 'House Owner updated successfully!');
    }

    public function tenants()
    {
        $tenants = Tenant::with(['flat.building', 'assignedBy'])->paginate(15);
        return view('admin.tenants.index', compact('tenants'));
    }

    public function createTenant()
    {
        $buildings = Building::with(['flats' => function($query) {
            $query->where('is_occupied', false);
        }])->get();
        
        return view('admin.tenants.create', compact('buildings'));
    }

    public function storeTenant(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:tenants',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'id_proof_type' => 'nullable|string',
            'id_proof_number' => 'nullable|string',
            'flat_id' => 'nullable|exists:flats,id',
            'move_in_date' => 'nullable|date',
            'security_deposit' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['assigned_by'] = auth()->user()->id;

        $tenant = Tenant::create($validated);

        if ($tenant->flat_id) {
            Flat::find($tenant->flat_id)->update(['is_occupied' => true]);
        }

        return redirect()->route('admin.tenants.index')
                        ->with('success', 'Tenant created successfully!');
    }

    public function editTenant(Tenant $tenant)
    {
        $buildings = Building::with(['flats'])->get();
        return view('admin.tenants.edit', compact('tenant', 'buildings'));
    }

    public function updateTenant(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:tenants,email,' . $tenant->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'id_proof_type' => 'nullable|string',
            'id_proof_number' => 'nullable|string',
            'flat_id' => 'nullable|exists:flats,id',
            'move_in_date' => 'nullable|date',
            'move_out_date' => 'nullable|date',
            'security_deposit' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $oldFlatId = $tenant->flat_id;
        $tenant->update($validated);

        if ($oldFlatId && $oldFlatId != $tenant->flat_id) {
            Flat::find($oldFlatId)->update(['is_occupied' => false]);
        }
        
        if ($tenant->flat_id) {
            Flat::find($tenant->flat_id)->update(['is_occupied' => true]);
        }

        return redirect()->route('admin.tenants.index')
                        ->with('success', 'Tenant updated successfully!');
    }

    public function destroyTenant(Tenant $tenant)
    {
        if ($tenant->flat_id) {
            Flat::find($tenant->flat_id)->update(['is_occupied' => false]);
        }
        
        $tenant->delete();

        return redirect()->route('admin.tenants.index')
                        ->with('success', 'Tenant removed successfully!');
    }

    private function generateTenantId()
    {
        do {
            $tenantId = mt_rand(100000, 999999);
        } while (User::where('tenant_id', $tenantId)->exists());
        
        return $tenantId;
    }
}
