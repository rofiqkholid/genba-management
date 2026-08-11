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
        Schema::create('KPICompanyActivity', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kpi_company_id');
            $table->integer('tahun');
            $table->string('bulan');
            $table->string('actual')->nullable();
            $table->string('status')->nullable();
            $table->string('problem_solve')->nullable();
            $table->timestamps();

            // Foreign key relation to KPICompany
            $table->foreign('kpi_company_id')
                  ->references('id')
                  ->on('KPICompany')
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
        Schema::dropIfExists('KPICompanyActivity');
    }
};
