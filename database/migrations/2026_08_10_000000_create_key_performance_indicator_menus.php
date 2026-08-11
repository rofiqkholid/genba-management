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
        // 1. Insert main menu Key Performance Indicator
        DB::table('t100_menus')->updateOrInsert(
            ['id' => 116],
            [
                'sequence_id' => 5,
                'level_menu_id' => 2,
                'group_id' => 10,
                'sub_group_id' => 116,
                'menu' => 'kpi',
                'menu_name' => 'Key Performance Indicator',
                'icon' => '<span></span>'
            ]
        );

        // 2. Insert submenus
        $submenus = [
            [
                'id' => 117,
                'sequence_id' => 1,
                'level_menu_id' => 3,
                'group_id' => 10,
                'sub_group_id' => 116,
                'menu' => 'kpi/company',
                'menu_name' => 'Company KPI',
                'icon' => '<span></span>'
            ],
            [
                'id' => 118,
                'sequence_id' => 2,
                'level_menu_id' => 3,
                'group_id' => 10,
                'sub_group_id' => 116,
                'menu' => 'kpi/department',
                'menu_name' => 'Departement KPI',
                'icon' => '<span></span>'
            ],
            [
                'id' => 119,
                'sequence_id' => 3,
                'level_menu_id' => 3,
                'group_id' => 10,
                'sub_group_id' => 116,
                'menu' => 'kpi/monthly-summary',
                'menu_name' => 'Monthly Summary',
                'icon' => '<span></span>'
            ],
            [
                'id' => 120,
                'sequence_id' => 4,
                'level_menu_id' => 3,
                'group_id' => 10,
                'sub_group_id' => 116,
                'menu' => 'kpi/print-report',
                'menu_name' => 'Print Report',
                'icon' => '<span></span>'
            ]
        ];

        foreach ($submenus as $sub) {
            DB::table('t100_menus')->updateOrInsert(['id' => $sub['id']], $sub);
        }

        // 3. Copy permissions from dashboard (ID 100) to all new menus
        $allMenuIds = [116, 117, 118, 119, 120];
        $permissions = DB::table('t100_user_menus_permission')->where('id_menus', 100)->get();

        foreach ($allMenuIds as $menuId) {
            foreach ($permissions as $perm) {
                DB::table('t100_user_menus_permission')->updateOrInsert(
                    [
                        'id_user' => $perm->id_user,
                        'id_menus' => $menuId
                    ],
                    [
                        'is_view' => $perm->is_view,
                        'is_delete' => $perm->is_delete
                    ]
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $allMenuIds = [116, 117, 118, 119, 120];
        DB::table('t100_user_menus_permission')->whereIn('id_menus', $allMenuIds)->delete();
        DB::table('t100_menus')->whereIn('id', $allMenuIds)->delete();
    }
};
