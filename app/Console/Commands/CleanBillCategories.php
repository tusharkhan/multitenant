<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanBillCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-bill-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean duplicate bill categories and merge them per tenant';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting bill categories cleanup...');
        
        // Get all bill categories grouped by tenant_id and name
        $categories = \App\Models\BillCategory::all()->groupBy(function ($item) {
            return $item->tenant_id . '_' . $item->name;
        });
        
        $cleaned = 0;
        $mergedBills = 0;
        
        foreach ($categories as $group) {
            if ($group->count() > 1) {
                // Keep the first category and merge others into it
                $keepCategory = $group->first();
                $duplicates = $group->slice(1);
                
                $this->info("Processing duplicates for '{$keepCategory->name}' (Tenant: {$keepCategory->tenant_id})");
                
                foreach ($duplicates as $duplicate) {
                    // Move all bills from duplicate to the kept category
                    $billsCount = $duplicate->bills()->count();
                    if ($billsCount > 0) {
                        $duplicate->bills()->update(['bill_category_id' => $keepCategory->id]);
                        $mergedBills += $billsCount;
                        $this->info("  Moved {$billsCount} bills from duplicate category");
                    }
                    
                    // Delete the duplicate
                    $duplicate->delete();
                    $cleaned++;
                }
                
                // Make building_id null for the kept category
                $keepCategory->update(['building_id' => null]);
            } else {
                // Single category, just make building_id null
                $group->first()->update(['building_id' => null]);
            }
        }
        
        $this->info("Cleanup completed!");
        $this->info("- Removed {$cleaned} duplicate categories");
        $this->info("- Merged {$mergedBills} bills");
        $this->info("- Set building_id to null for all categories");
        
        return 0;
    }
}
