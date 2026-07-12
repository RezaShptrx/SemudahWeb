<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone', 20);
            $table->string('status', 50)->default('antri');
            $table->decimal('total_price', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('final_price', 12, 2);
            $table->string('payment_method', 50)->default('qris');
            $table->string('payment_status', 50)->default('pending');
            $table->text('notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->dateTime('estimated_completion_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('taken_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->timestamps();

            $table->index('order_number');
            $table->index('customer_phone');
            $table->index('status');
            $table->index('payment_status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
