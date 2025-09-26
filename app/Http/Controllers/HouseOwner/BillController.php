<?php

namespace App\Http\Controllers\HouseOwner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Building;
use App\Models\Flat;
use App\Models\BillCategory;
use App\Models\Bill;
use App\Notifications\BillCreated;
use App\Notifications\BillPaid;

class BillController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('tenant.scope');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isHouseOwner()) {
                abort(403, 'Access denied. House Owner only.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $bills = Bill::with(['flat.building', 'billCategory'])
                    ->forTenant(Auth::user()->tenant_id)
                    ->latest()
                    ->paginate(20);
                    
        $buildings = Auth::user()->buildings;
                    
        return view('house_owner.bills.index', compact('bills', 'buildings'));
    }

    public function create()
    {
        $buildings = Auth::user()->buildings()->with('flats')->get();
        $billCategories = BillCategory::where('tenant_id', Auth::user()->tenant_id)
                                     ->where('is_active', true)
                                     ->get();
        return view('house_owner.bills.create', compact('buildings', 'billCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'flat_id' => 'required|exists:flats,id',
            'bill_category_id' => 'required|exists:bill_categories,id',
            'bill_month' => 'required|date_format:Y-m',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date|after:today',
            'notes' => 'nullable|string',
        ]);

        $flat = Flat::find($validated['flat_id']);
        
        $existingBill = Bill::where('flat_id', $validated['flat_id'])
                           ->where('bill_category_id', $validated['bill_category_id'])
                           ->where('bill_month', $validated['bill_month'])
                           ->first();

        if ($existingBill) {
            return back()->withErrors(['bill_month' => 'Bill already exists for this flat, category, and month.']);
        }

        $previousDue = Bill::where('flat_id', $validated['flat_id'])
                          ->where('bill_category_id', $validated['bill_category_id'])
                          ->where('status', 'unpaid')
                          ->where('bill_month', '<', $validated['bill_month'])
                          ->sum('total_amount');

        $validated['previous_due'] = $previousDue;
        $validated['total_amount'] = $validated['amount'] + $previousDue;
        $validated['created_by'] = Auth::id();
        $validated['tenant_id'] = Auth::user()->tenant_id;

        $bill = Bill::create($validated);

        if ($request->has('send_notification') && $bill->flat->currentTenant) {
            $bill->flat->currentTenant->notify(new BillCreated($bill));
        }

        Auth::user()->notify(new BillCreated($bill));

        return redirect()->route('house_owner.bills.index')
                        ->with('success', 'Bill created successfully!');
    }

    public function show(Bill $bill)
    {
        $bill->load(['flat.building', 'billCategory', 'createdBy']);
        return view('house_owner.bills.show', compact('bill'));
    }

    public function edit(Bill $bill)
    {
        if ($bill->status === 'paid') {
            return redirect()->back()->with('error', 'Cannot edit paid bills.');
        }

        $buildings = Auth::user()->buildings()->with('flats')->get();
        $billCategories = BillCategory::where('tenant_id', Auth::user()->tenant_id)
                                     ->where('is_active', true)
                                     ->get();
        return view('house_owner.bills.edit', compact('bill', 'buildings', 'billCategories'));
    }

    public function update(Request $request, Bill $bill)
    {
        if ($bill->status === 'paid') {
            return redirect()->back()->with('error', 'Cannot edit paid bills.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['total_amount'] = $validated['amount'] + $bill->previous_due;

        $bill->update($validated);

        return redirect()->route('house_owner.bills.index')
                        ->with('success', 'Bill updated successfully!');
    }

    public function markAsPaid(Request $request, Bill $bill)
    {
        $validated = $request->validate([
            'payment_method' => 'nullable|string|max:50',
            'transaction_reference' => 'nullable|string|max:100',
        ]);

        $bill->markAsPaid($validated['payment_method'], $validated['transaction_reference']);

        Auth::user()->notify(new BillPaid($bill));

        return redirect()->route('house_owner.bills.index')
                        ->with('success', 'Bill marked as paid successfully!');
    }

    public function getFlats(Building $building)
    {
        $flats = $building->flats()->select('id', 'flat_number', 'owner_name')->get();
        return response()->json($flats);
    }

    public function getBillCategories(Building $building)
    {
        $categories = $building->billCategories()->select('id', 'name')->get();
        return response()->json($categories);
    }

    public function bulkCreate()
    {
        $buildings = Auth::user()->buildings()->with('flats')->get();
        $billCategories = BillCategory::where('tenant_id', Auth::user()->tenant_id)
                                     ->where('is_active', true)
                                     ->get();
        return view('house_owner.bills.bulk_create', compact('buildings', 'billCategories'));
    }

    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'bill_category_id' => 'required|exists:bill_categories,id',
            'bill_month' => 'required|date_format:Y-m',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date|after:today',
            'flat_ids' => 'required|array',
            'flat_ids.*' => 'exists:flats,id',
            'notes' => 'nullable|string',
        ]);

        $building = Building::find($validated['building_id']);
        $createdBills = 0;
        $skippedBills = 0;

        foreach ($validated['flat_ids'] as $flatId) {
            $existingBill = Bill::where('flat_id', $flatId)
                               ->where('bill_category_id', $validated['bill_category_id'])
                               ->where('bill_month', $validated['bill_month'])
                               ->first();

            if ($existingBill) {
                $skippedBills++;
                continue;
            }

            $previousDue = Bill::where('flat_id', $flatId)
                              ->where('bill_category_id', $validated['bill_category_id'])
                              ->where('status', 'unpaid')
                              ->where('bill_month', '<', $validated['bill_month'])
                              ->sum('total_amount');

            Bill::create([
                'flat_id' => $flatId,
                'bill_category_id' => $validated['bill_category_id'],
                'bill_month' => $validated['bill_month'],
                'amount' => $validated['amount'],
                'previous_due' => $previousDue,
                'total_amount' => $validated['amount'] + $previousDue,
                'due_date' => $validated['due_date'],
                'notes' => $validated['notes'],
                'created_by' => Auth::id(),
                'tenant_id' => Auth::user()->tenant_id,
            ]);

            $createdBills++;
        }

        $message = "Bulk bill creation completed. Created: {$createdBills} bills";
        if ($skippedBills > 0) {
            $message .= ", Skipped: {$skippedBills} bills (already exist)";
        }

        return redirect()->route('house_owner.bills.index')->with('success', $message);
    }
}
