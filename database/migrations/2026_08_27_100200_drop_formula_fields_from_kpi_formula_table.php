<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('KPIFormula', function (Blueprint $table) {
            if (Schema::hasColumn('KPIFormula', 'formula_type')) {
                $table->dropColumn('formula_type');
            }
            if (Schema::hasColumn('KPIFormula', 'result')) {
                $table->dropColumn('result');
            }
            if (Schema::hasColumn('KPIFormula', 'cell')) {
                $table->dropColumn('cell');
            }
        });
    }

    public function down()
    {
        Schema::table('KPIFormula', function (Blueprint $table) {
            $table->string('formula_type', 50)->nullable();
            $table->text('result')->nullable();
            $table->text('cell')->nullable();
        });
    }
};
