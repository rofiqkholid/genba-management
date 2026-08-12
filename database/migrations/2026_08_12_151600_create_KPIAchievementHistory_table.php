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
        Schema::create('KPIAchievementHistory', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kpi_list_id');
            $table->integer('year');
            $table->string('target')->nullable();
            $table->string('achievement')->nullable();
            $table->timestamps();

            // Foreign key to KPIList
            $table->foreign('kpi_list_id')
                  ->references('id')
                  ->on('KPIList')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('KPIAchievementHistory');
    }
};
