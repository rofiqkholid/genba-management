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
        Schema::create('KPICompanyActivityProblem', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kpi_company_activity_id')->unique();
            $table->text('problem_description')->nullable();
            $table->string('problem_image')->nullable();
            $table->text('root_cause')->nullable();
            $table->string('root_cause_image')->nullable();
            $table->text('temporary_action')->nullable();
            $table->string('temporary_action_image')->nullable();
            $table->text('permanent_action')->nullable();
            $table->string('permanent_action_image')->nullable();
            $table->string('machine')->nullable();
            $table->string('material')->nullable();
            $table->string('man')->nullable();
            $table->string('method')->nullable();
            $table->string('money')->nullable();
            $table->string('environment')->nullable();
            $table->date('start_date')->nullable();
            $table->date('finish_date')->nullable();
            $table->string('closed_status')->nullable();
            $table->string('pic_dept')->nullable();
            $table->string('follow_up_by')->nullable();
            $table->string('evidence')->nullable();
            $table->timestamps();

            $table->foreign('kpi_company_activity_id')
                  ->references('id')
                  ->on('KPICompanyActivity')
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
        Schema::dropIfExists('KPICompanyActivityProblem');
    }
};
