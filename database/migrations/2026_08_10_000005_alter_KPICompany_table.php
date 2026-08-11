<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('KPICompany', function (Blueprint $table) {
            // Drop current_actual column
            $table->dropColumn('current_actual');

            // Rename current_target to target
            $table->renameColumn('current_target', 'target');

            // Add new columns
            $table->string('operator')->nullable()->after('department_code');
            $table->string('unit')->nullable()->after('target');
            $table->string('periode')->nullable()->after('unit');
            $table->text('calculation_method')->nullable()->after('periode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('KPICompany', function (Blueprint $table) {
            $table->renameColumn('target', 'current_target');
            $table->string('current_actual')->nullable()->after('current_target');

            $table->dropColumn(['operator', 'unit', 'periode', 'calculation_method']);
        });
    }
};
