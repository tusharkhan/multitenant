<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->cleanDuplicateCategories();
        
        Schema::table('bill_categories', function (Blueprint $table) {
            $table->dropForeign(['building_id']);
            $table->dropUnique(['name', 'building_id']);
            $table->unsignedBigInteger('building_id')->nullable()->change();
            $table->unique(['name', 'tenant_id']);
            $table->foreign('building_id')->references('id')->on('buildings')->onDelete('set null');
        });
    }
    
    private function cleanDuplicateCategories()
    {
        $categories = DB::table('bill_categories')
            ->select('tenant_id', 'name', DB::raw('MIN(id) as keep_id, GROUP_CONCAT(id) as all_ids'))
            ->groupBy('tenant_id', 'name')
            ->having(DB::raw('COUNT(*)'), '>', 1)
            ->get();
            
        foreach ($categories as $categoryGroup) {
            $allIds = explode(',', $categoryGroup->all_ids);
            $keepId = $categoryGroup->keep_id;
            $duplicateIds = array_filter($allIds, function($id) use ($keepId) {
                return $id != $keepId;
            });
            
            if (!empty($duplicateIds)) {
                DB::table('bill_categories')->whereIn('id', $duplicateIds)->delete();
            }
            
            
            DB::table('bill_categories')->where('id', $keepId)->update(['building_id' => null]);
        }
        
        
        DB::table('bill_categories')
            ->whereNotNull('building_id')
            ->update(['building_id' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bill_categories', function (Blueprint $table) {
            $table->dropUnique(['name', 'tenant_id']);
            $table->dropForeign(['building_id']);
            $table->unsignedBigInteger('building_id')->nullable(false)->change();
            $table->unique(['name', 'building_id']);
            $table->foreign('building_id')->references('id')->on('buildings')->onDelete('cascade');
        });
    }
};
