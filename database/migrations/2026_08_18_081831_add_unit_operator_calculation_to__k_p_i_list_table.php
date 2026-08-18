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
        Schema::table('KPIList', function (Blueprint $table) {
            $table->string('unit')->nullable()->after('target');
            $table->string('operator')->nullable()->after('unit');
            $table->text('calculation_method')->nullable()->after('operator');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('KPIList', function (Blueprint $table) {
            $table->dropColumn(['unit', 'operator', 'calculation_method']);
        });
    }
};
