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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id'); // Link to the specific dealer
            $table->unsignedBigInteger('vehicle_id')->nullable(); // Link to the specific car
            $table->string('name');
            $table->string('mobile', 20);
            $table->string('email')->nullable();
            $table->string('vehicle_url')->nullable();
            $table->text('message')->nullable();
            
            // Operational Lead Lifecycle States
            $table->enum('status', [
                'new',
                'contacted',
                'token_received',
                'closed'
            ])->default('new');
            
            // Financial Ledger Auditing metrics
            $table->decimal('final_closing_rate', 12, 2)->nullable();
            $table->decimal('calculated_commission', 12, 2)->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Set up indexing foreign keys to accelerate dashboard data aggregation speed
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
