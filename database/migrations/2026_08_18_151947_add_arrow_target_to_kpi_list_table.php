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
        if (!Schema::hasColumn('KPIList', 'arrow_target')) {
            Schema::table('KPIList', function (Blueprint $table) {
                $table->string('arrow_target', 10)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('KPIList', 'arrow_target')) {
            Schema::table('KPIList', function (Blueprint $table) {
                $table->dropColumn('arrow_target');
            });
        }
    }
};
