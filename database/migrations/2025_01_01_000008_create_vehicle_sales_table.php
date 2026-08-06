<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->unique()->constrained('vehicles')->cascadeOnDelete();

            $table->string('purchaser_name');
            $table->string('purchaser_address')->nullable();
            $table->string('purchaser_mobile')->nullable();

            $table->string('reference_medium')->nullable()->comment('Branch name / medium of sale');

            $table->date('sale_date'); // D.O.S
            $table->decimal('sale_rate', 12, 2)->default(0);
            $table->decimal('commission', 12, 2)->default(0);
            // net_rate = sale_rate - commission
            $table->decimal('net_rate', 12, 2)->default(0);
            // profit_loss = sale net_rate - purchase net_rate
            $table->decimal('profit_loss', 12, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_sales');
    }
};
