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
        Schema::table('vehicles', function (Blueprint $table) {

            $table->unsignedSmallInteger('make_year')
                ->nullable()
                ->after('model');

            $table->unsignedInteger('km_driven')
                ->nullable()
                ->after('make_year');

            $table->string('fuel_type', 30)
                ->nullable()
                ->after('km_driven');

            $table->string('transmission', 30)
                ->nullable()
                ->after('fuel_type');

            $table->string('ownership', 30)
                ->nullable()
                ->after('transmission');

            $table->unsignedInteger('engine_cc')
                ->nullable()
                ->after('ownership');

            $table->string('engine_description', 150)
                ->nullable()
                ->after('engine_cc');

            $table->decimal('engine_power_ps', 8, 2)
                ->nullable()
                ->after('engine_description');

            $table->decimal('mileage_claimed', 6, 2)
                ->nullable()
                ->after('engine_power_ps');

            $table->unsignedTinyInteger('seating_capacity')
                ->nullable()
                ->after('mileage_claimed');

            $table->decimal('fuel_tank', 6, 2)
                ->nullable()
                ->after('seating_capacity');

            $table->string('colour', 80)
                ->nullable()
                ->after('fuel_tank');

            $table->string('insurance_type', 80)
                ->nullable()
                ->after('colour');

            $table->date('insurance_valid_till')
                ->nullable()
                ->after('insurance_type');

            $table->string('registration_no', 30)
                ->nullable()
                ->after('insurance_valid_till');

            $table->json('inspection_highlights')
                ->nullable()
                ->after('registration_no');

            $table->json('features')
                ->nullable()
                ->after('inspection_highlights');

            $table->index('make_year');
            $table->index('fuel_type');
            $table->index('transmission');
            $table->index('km_driven');
            $table->index('registration_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {

            $table->dropIndex(['make_year']);
            $table->dropIndex(['fuel_type']);
            $table->dropIndex(['transmission']);
            $table->dropIndex(['km_driven']);
            $table->dropIndex(['registration_no']);

            $table->dropColumn([
                'make_year',
                'km_driven',
                'fuel_type',
                'transmission',
                'ownership',
                'engine_cc',
                'engine_description',
                'engine_power_ps',
                'mileage_claimed',
                'seating_capacity',
                'fuel_tank',
                'colour',
                'insurance_type',
                'insurance_valid_till',
                'registration_no',
                'inspection_highlights',
                'features',
            ]);
        });
    }
};
