<?php

namespace App\Http\Controllers\HouseOwner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\Flat;
use App\Models\BillCategory;

class HouseOwnerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('tenant.scope');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isHouseOwner()) {
                abort(403, 'Access denied. House Owner only.');
            }
            return $next($request);
        });
    }

    // Building Management
    public function buildings()
    {
        $buildings = auth()->user()->buildings()->with(['flats'])->paginate(10);
        return view('house_owner.buildings.index', compact('buildings'));
    }

    public function createBuilding()
    {
        return view('house_owner.buildings.create');
    }

    public function storeBuilding(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'description' => 'nullable|string',
        ]);

        $validated['owner_id'] = auth()->id();
        $validated['tenant_id'] = auth()->user()->tenant_id;

        $building = Building::create($validated);

        $this->createDefaultBillCategories();

        return redirect()->route('house_owner.buildings.index')
                        ->with('success', 'Building created successfully!');
    }

    public function editBuilding(Building $building)
    {
        return view('house_owner.buildings.edit', compact('building'));
    }

    public function updateBuilding(Request $request, Building $building)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $building->update($validated);

        return redirect()->route('house_owner.buildings.index')
                        ->with('success', 'Building updated successfully!');
    }

    public function flats(Building $building)
    {
        $flats = $building->flats()->paginate(15);
        return view('house_owner.flats.index', compact('building', 'flats'));
    }

    public function createFlat(Building $building)
    {
        return view('house_owner.flats.create', compact('building'));
    }

    public function storeFlat(Request $request, Building $building)
    {
        $validated = $request->validate([
            'flat_number' => 'required|string|max:20',
            'owner_name' => 'required|string|max:255',
            'owner_phone' => 'nullable|string|max:20',
            'owner_email' => 'nullable|email|max:255',
            'owner_address' => 'nullable|string',
            'carpet_area' => 'nullable|numeric|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($building->flats()->where('flat_number', $validated['flat_number'])->exists()) {
            return back()->withErrors(['flat_number' => 'Flat number already exists in this building.']);
        }

        $validated['building_id'] = $building->id;
        $validated['tenant_id'] = auth()->user()->tenant_id;

        Flat::create($validated);

        return redirect()->route('house_owner.flats.index', $building)
                        ->with('success', 'Flat created successfully!');
    }

    public function editFlat(Building $building, Flat $flat)
    {
        return view('house_owner.flats.edit', compact('building', 'flat'));
    }

    public function updateFlat(Request $request, Building $building, Flat $flat)
    {
        $validated = $request->validate([
            'flat_number' => 'required|string|max:20',
            'owner_name' => 'required|string|max:255',
            'owner_phone' => 'nullable|string|max:20',
            'owner_email' => 'nullable|email|max:255',
            'owner_address' => 'nullable|string',
            'carpet_area' => 'nullable|numeric|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
            'is_occupied' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($building->flats()->where('flat_number', $validated['flat_number'])
                           ->where('id', '!=', $flat->id)->exists()) {
            return back()->withErrors(['flat_number' => 'Flat number already exists in this building.']);
        }

        $flat->update($validated);

        return redirect()->route('house_owner.flats.index', $building)
                        ->with('success', 'Flat updated successfully!');
    }

    public function destroyFlat(Building $building, Flat $flat)
    {
        $flat->delete();

        return redirect()->route('house_owner.flats.index', $building)
                        ->with('success', 'Flat deleted successfully!');
    }

    public function billCategories()
    {
        $billCategories = BillCategory::where('tenant_id', auth()->user()->tenant_id)
            ->withCount('bills')
            ->withSum('bills', 'total_amount')
            ->get();
            
        return view('house_owner.bill_categories.index', compact('billCategories'));
    }

    public function createBillCategory()
    {
        return view('house_owner.bill_categories.create');
    }

    public function storeBillCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if (BillCategory::where('tenant_id', auth()->user()->tenant_id)
                       ->where('name', $validated['name'])
                       ->exists()) {
            return back()->withErrors(['name' => 'Bill category already exists.']);
        }

        $validated['tenant_id'] = auth()->user()->tenant_id;
        $validated['is_active'] = $request->has('is_active');

        BillCategory::create($validated);

        return redirect()->route('house_owner.bill_categories.index')
                        ->with('success', 'Bill category created successfully!');
    }

    public function editBillCategory(BillCategory $billCategory)
    {
        if ($billCategory->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        return response()->json($billCategory);
    }

    public function updateBillCategory(Request $request, BillCategory $billCategory)
    {
        if ($billCategory->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if (BillCategory::where('tenant_id', auth()->user()->tenant_id)
                       ->where('name', $validated['name'])
                       ->where('id', '!=', $billCategory->id)
                       ->exists()) {
            return back()->withErrors(['name' => 'Bill category already exists.']);
        }

        $validated['is_active'] = $request->has('is_active');
        $billCategory->update($validated);

        return redirect()->route('house_owner.bill_categories.index')
                        ->with('success', 'Bill category updated successfully!');
    }

    public function toggleBillCategoryStatus(BillCategory $billCategory)
    {
        
        if ($billCategory->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $billCategory->update(['is_active' => !$billCategory->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Category status updated successfully!'
        ]);
    }

    public function destroyBillCategory(BillCategory $billCategory)
    {
        if ($billCategory->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        if ($billCategory->bills()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete category with associated bills.']);
        }

        $billCategory->delete();

        return redirect()->route('house_owner.bill_categories.index')
                        ->with('success', 'Bill category deleted successfully!');
    }

    private function createDefaultBillCategories()
    {
        if (BillCategory::where('tenant_id', auth()->user()->tenant_id)->exists()) {
            return;
        }

        $defaultCategories = [
            'Maintenance',
            'Electricity',
            'Water',
            'Internet',
        ];
        
        foreach ($defaultCategories as $categoryName) {
            BillCategory::create([
                'name' => $categoryName,
                'tenant_id' => auth()->user()->tenant_id,
                'is_active' => true,
            ]);
        }
    }
}
