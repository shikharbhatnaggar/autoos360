<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_documents', function (Blueprint $table) {

            $table->id();

            $table->foreignId('vehicle_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
             * vehicle = RC, PUC, Fitness, Tax, Permit, NOC, Insurance
             * seller  = Seller ID proof
             * buyer   = Buyer ID proof
             */
            $table->string('section', 20);

            /*
             * registration_certificate
             * puc
             * fitness
             * road_tax
             * permit
             * noc
             * insurance
             * aadhaar
             * driving_license
             * passport
             */
            $table->string('document_type', 50);

            $table->string('document_name', 150);

            $table->string('document_no', 100)
                ->nullable();

            $table->date('valid_till')
                ->nullable();

            $table->string('file_path', 500)
                ->nullable();

            $table->string('original_file_name', 255)
                ->nullable();

            $table->string('mime_type', 50)
                ->nullable();

            $table->unsignedInteger('file_size')
                ->nullable();

            $table->timestamps();

            $table->index(['vehicle_id', 'section']);
            $table->index(['vehicle_id', 'document_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_documents');
    }
};