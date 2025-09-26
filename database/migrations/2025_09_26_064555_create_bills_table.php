<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('flat_id');
            $table->unsignedBigInteger('bill_category_id');
            $table->string('bill_month'); 
            $table->decimal('amount', 10, 2);
            $table->decimal('previous_due', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['unpaid', 'paid', 'overdue'])->default('unpaid');
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->string('payment_method')->nullable(); 
            $table->string('transaction_reference')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('tenant_id'); 
            $table->timestamps();

            $table->foreign('flat_id')->references('id')->on('flats')->onDelete('cascade');
            $table->foreign('bill_category_id')->references('id')->on('bill_categories')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['flat_id', 'bill_category_id', 'bill_month']);
            $table->index(['tenant_id', 'flat_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
