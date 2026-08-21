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
            for ($i = 1; $i <= 15; $i++) {
                $table->double('val_' . $i)->nullable();
            }
            $table->string('formula_type', 50)->nullable();
        });
    }

    public function down()
    {
        Schema::table('KPIFormula', function (Blueprint $table) {
            for ($i = 1; $i <= 15; $i++) {
                $table->dropColumn('val_' . $i);
            }
            $table->dropColumn('formula_type');
        });
    }
};
