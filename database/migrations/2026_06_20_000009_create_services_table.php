<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price_bw', 12, 2)->default(0);
            $table->decimal('price_color', 12, 2)->default(0);
            $table->decimal('price_duplex', 12, 2)->default(0);
            $table->decimal('price_a4', 12, 2)->default(0);
            $table->decimal('price_f4', 12, 2)->default(0);
            $table->decimal('price_a3', 12, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
