<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('KPIFormula', function (Blueprint $table) {
            // Drop old columns
            for ($i = 1; $i <= 15; $i++) {
                if (Schema::hasColumn('KPIFormula', 'val_' . $i)) {
                    $table->dropColumn('val_' . $i);
                }
            }
            
            // Add new Comp 1 to 20 columns
            for ($i = 1; $i <= 20; $i++) {
                $table->string('comp_' . $i)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('KPIFormula', function (Blueprint $table) {
            for ($i = 1; $i <= 20; $i++) {
                if (Schema::hasColumn('KPIFormula', 'comp_' . $i)) {
                    $table->dropColumn('comp_' . $i);
                }
            }
            
            for ($i = 1; $i <= 15; $i++) {
                $table->double('val_' . $i)->nullable();
            }
        });
    }
};
