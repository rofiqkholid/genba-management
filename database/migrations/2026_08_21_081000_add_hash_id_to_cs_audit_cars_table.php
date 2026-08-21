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
        Schema::table('CsAuditCar', function (Blueprint $table) {
            if (!Schema::hasColumn('CsAuditCar', 'hash_id')) {
                $table->string('hash_id', 50)->nullable();
            }
        });

        // Populate existing rows
        $rows = DB::table('CsAuditCar')->get();
        foreach ($rows as $row) {
            $hash = strtolower(\Illuminate\Support\Str::random(3) . '-' . \Illuminate\Support\Str::random(3) . '-' . \Illuminate\Support\Str::random(3));
            DB::table('CsAuditCar')->where('id', $row->id)->update(['hash_id' => $hash]);
        }

        // Now add unique index
        Schema::table('CsAuditCar', function (Blueprint $table) {
            $table->unique('hash_id');
        });
    }

    public function down()
    {
        Schema::table('CsAuditCar', function (Blueprint $table) {
            if (Schema::hasColumn('CsAuditCar', 'hash_id')) {
                $table->dropColumn('hash_id');
            }
        });
    }
};
