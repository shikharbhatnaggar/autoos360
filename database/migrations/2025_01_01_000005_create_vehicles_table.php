<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->string('sr_no')->comment('Register serial no, e.g. SR-019');
            $table->string('memo_no')->nullable();
            $table->string('vehicle_no')->comment('Registration number of the vehicle');
            $table->string('model');
            $table->enum('status', ['in_stock', 'sold'])->default('in_stock');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['branch_id', 'sr_no']);
            $table->index('vehicle_no');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
