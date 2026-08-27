<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('KPICompanyActivity', function (Blueprint $table) {
            for ($i = 1; $i <= 20; $i++) {
                $table->string('comp_' . $i)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('KPICompanyActivity', function (Blueprint $table) {
            for ($i = 1; $i <= 20; $i++) {
                if (Schema::hasColumn('KPICompanyActivity', 'comp_' . $i)) {
                    $table->dropColumn('comp_' . $i);
                }
            }
        });
    }
};
