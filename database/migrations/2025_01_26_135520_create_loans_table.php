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
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('item_name');
            $table->decimal('loan_amount', 10, 0);
            $table->decimal('down_payment', 10, 0)->nullable()->default(0);
            $table->decimal('monthly_installment', 10, 0);
            $table->integer('remaining_months')->default(0)->nullable();
            $table->decimal('outstanding_balance', 10, 0);
            $table->decimal('returned_money', 10, 0)->nullable();
            $table->date('buying_date');
            $table->enum('status', ['active', ' ', 'overdue'])->default('active');
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
