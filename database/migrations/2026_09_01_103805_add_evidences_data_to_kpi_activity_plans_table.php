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
        Schema::table('kpi_activity_plans', function (Blueprint $table) {
            $table->text('evidences_data')->nullable()->after('months_data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kpi_activity_plans', function (Blueprint $table) {
            $table->dropColumn('evidences_data');
        });
    }
};
