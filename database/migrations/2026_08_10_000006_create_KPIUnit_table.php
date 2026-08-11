<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('KPIUnit', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Insert some default standard units
        $defaultUnits = [
            ['name' => '%', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'case', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'hours', 'created_at' => now(), 'updated_at' => now()]
        ];

        DB::table('KPIUnit')->insert($defaultUnits);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('KPIUnit');
    }
};
