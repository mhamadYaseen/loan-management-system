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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade'); // Link to Customers
            $table->foreignId('guarantor_id')->constrained()->onDelete('cascade'); // Link to Guarantors
            $table->string('item_name'); // Name of the item
            $table->decimal('loan_amount', 10, 2); // Total loan amount
            $table->decimal('down_payment', 10, 2)->nullable(); // Initial payment
            $table->decimal('outstanding_balance', 10, 2); // Remaining balance
            $table->decimal('monthly_installment', 10, 2); // Monthly payment amount
            $table->integer('months_required'); // Total number of months for repayment
            $table->enum('status', ['active', 'completed', 'overdue'])->default('active'); // Loan status
            $table->date('buying_date'); // Date the loan was issued
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
