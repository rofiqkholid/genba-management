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
        Schema::create('KPIList', function (Blueprint $table) {
            $table->id();
            $table->string('no_kpi');
            $table->string('objective');
            $table->text('definition');
            $table->string('pillar');
            $table->string('category');
            $table->string('target');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('KPIList');
    }
};
