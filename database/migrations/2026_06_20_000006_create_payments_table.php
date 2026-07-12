<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->enum('method', ['qris', 'gopay', 'shopeepay', 'virtual_account', 'transfer_bank', 'tunai']);
            $table->enum('status', ['pending', 'success', 'failed', 'expired'])->default('pending');
            $table->string('transaction_id')->nullable();
            $table->dateTime('transaction_time')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->text('verification_note')->nullable();
            $table->json('midtrans_response')->nullable();
            $table->timestamps();

            $table->index('order_id');
            $table->index('transaction_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
