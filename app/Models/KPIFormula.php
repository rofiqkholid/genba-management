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
        'comp_1', 'comp_2', 'comp_3', 'comp_4', 'comp_5',
        'comp_6', 'comp_7', 'comp_8', 'comp_9', 'comp_10',
        'comp_11', 'comp_12', 'comp_13', 'comp_14', 'comp_15',
        'comp_16', 'comp_17', 'comp_18', 'comp_19', 'comp_20',
    ];
}
