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
        Schema::table('KPIFormula', function (Blueprint $table) {
            $table->string('calc_operator', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
     {
         Schema::table('KPIFormula', function (Blueprint $table) {
             $table->dropColumn('calc_operator');
         });
     }
};
