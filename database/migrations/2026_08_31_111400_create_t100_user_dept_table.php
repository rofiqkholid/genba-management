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
        // 1. Drop department column from users table if it exists
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'department')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('department');
            });
        }

        // 2. Create t100_user_dept table
        if (!Schema::hasTable('t100_user_dept')) {
            Schema::create('t100_user_dept', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('id_user');
                $table->string('department', 50)->nullable();
                $table->timestamps();
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
        // 1. Drop t100_user_dept table
        Schema::dropIfExists('t100_user_dept');

        // 2. Add department column back to users table
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'department')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('department')->nullable()->after('role_id');
            });
        }
    }
};
