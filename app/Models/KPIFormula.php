<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KPIFormula extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'KPIFormula';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kpi_list_id',
        'cell',
        'result',
        'formula_type',
        'val_1', 'val_2', 'val_3', 'val_4', 'val_5',
        'val_6', 'val_7', 'val_8', 'val_9', 'val_10',
        'val_11', 'val_12', 'val_13', 'val_14', 'val_15',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'cell' => 'array',
    ];
}
