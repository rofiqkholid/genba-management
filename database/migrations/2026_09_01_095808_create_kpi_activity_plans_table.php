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
        Schema::create('kpi_activity_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kpi_company_id');
            $table->text('support_topic')->nullable();
            $table->text('activity_plan');
            $table->string('pic')->nullable();
            $table->text('supporting')->nullable();
            $table->string('quick_plan')->nullable();
            $table->integer('start_month')->default(1);
            $table->integer('end_month')->default(1);
            $table->text('months_data')->nullable(); // Store JSON array of active months / checklist
            $table->decimal('success_rate', 5, 2)->default(0.00);
            $table->string('status')->default('On Progress');
            $table->text('remark')->nullable();
            $table->timestamps();

            $table->index('kpi_company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kpi_activity_plans');
    }
};
