<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->unique()->constrained('vehicles')->cascadeOnDelete();

            $table->string('seller_name');
            $table->string('seller_address')->nullable();
            $table->string('seller_mobile')->nullable();

            $table->enum('reference_type', ['direct', 'broker'])->default('direct');
            $table->foreignId('broker_id')->nullable()->constrained('brokers')->nullOnDelete();

            $table->date('purchase_date'); // D.O.P
            $table->decimal('purchase_rate', 12, 2)->default(0);
            $table->decimal('commission', 12, 2)->default(0);
            $table->decimal('expenses_total', 12, 2)->default(0); // synced from vehicle_expenses
            // net_rate = purchase_rate + commission + expenses_total
            $table->decimal('net_rate', 12, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_purchases');
    }
};
