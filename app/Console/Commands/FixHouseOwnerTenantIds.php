<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixHouseOwnerTenantIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-house-owner-tenant-ids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix null tenant_id for house_owner users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $houseOwners = \App\Models\User::where('role', 'house_owner')
                                      ->whereNull('tenant_id')
                                      ->get();
        
        if ($houseOwners->isEmpty()) {
            $this->info('No house owners with null tenant_id found.');
            return 0;
        }
        
        $this->info("Found {$houseOwners->count()} house owners with null tenant_id");
        
        foreach ($houseOwners as $index => $user) {
            $tenantId = 100001 + $index;
            $user->update(['tenant_id' => $tenantId]);
            $this->info("Updated {$user->name} ({$user->email}) with tenant_id: {$tenantId}");
        }
        
        $this->info('All house owner tenant IDs have been fixed!');
        return 0;
    }
}
