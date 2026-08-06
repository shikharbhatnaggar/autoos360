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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name')->nullable();
            $table->string('slug')->unique(); // created, updated, deleted, sold, etc.
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('gst_number')->nullable();
            $table->string('website')->nullable();
            $table->string('timezone')->default('Asia/Kolkata');
            $table->string('currency')->default('INR');
            $table->unsignedBigInteger('subscription_plan_id')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            $table->foreign('subscription_plan_id')->references('id')->on('subscription_plans')->nullOnDelete();
            $table->enum('status', [
                'active',
                'inactive',
                'suspended'
            ])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
