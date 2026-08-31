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
        Illuminate\Support\Facades\DB::statement("ALTER TABLE KPIFormula ALTER COLUMN calc_operator NVARCHAR(500) NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Illuminate\Support\Facades\DB::statement("ALTER TABLE KPIFormula ALTER COLUMN calc_operator NVARCHAR(50) NULL");
    }
};
