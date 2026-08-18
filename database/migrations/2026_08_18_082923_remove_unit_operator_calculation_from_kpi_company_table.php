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
            $table->dropColumn(['unit', 'operator', 'calculation_method']);
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
            $table->string('operator')->nullable()->after('department_code');
            $table->string('unit')->nullable()->after('target');
            $table->text('calculation_method')->nullable()->after('periode');
        });
    }
};
