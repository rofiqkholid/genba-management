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
        // 1. Insert "KPI List" menu under "Data Master" (group_id = 6, sub_group_id = 95)
        DB::table('t100_menus')->updateOrInsert(
            ['id' => 121],
            [
                'sequence_id' => 9,
                'level_menu_id' => 3,
                'group_id' => 6,
                'sub_group_id' => 95,
                'menu' => 'data-master/kpi-list',
                'menu_name' => 'KPI List',
                'icon' => '<span></span>'
            ]
        );

        // 2. Copy permissions from parent menu (ID 95) to the new menu
        $permissions = DB::table('t100_user_menus_permission')->where('id_menus', 95)->get();
        foreach ($permissions as $perm) {
            DB::table('t100_user_menus_permission')->updateOrInsert(
                [
                    'id_user' => $perm->id_user,
                    'id_menus' => 121
                ],
                [
                    'is_view' => $perm->is_view,
                    'is_delete' => $perm->is_delete
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('t100_user_menus_permission')->where('id_menus', 121)->delete();
        DB::table('t100_menus')->where('id', 121)->delete();
    }
};
