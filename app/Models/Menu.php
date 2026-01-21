<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 't100_menus';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sequence_id',
        'level_menu_id',
        'group_id',
        'sub_group_id',
        'menu',
        'menu_name',
        'icon',
    ];

    /**
     * Get menus ordered by sequence
     */
    public static function getOrderedMenus()
    {
        return self::orderBy('sequence_id', 'asc')->get();
    }
}
