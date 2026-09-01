<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KPIActivityPlan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kpi_activity_plans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kpi_company_id',
        'support_topic',
        'activity_plan',
        'pic',
        'supporting',
        'quick_plan',
        'start_month',
        'end_month',
        'months_data',
        'success_rate',
        'status',
        'remark',
    ];

    /**
     * Cast attributes to native types.
     *
     * @var array
     */
    protected $casts = [
        'months_data' => 'array',
        'success_rate' => 'float',
    ];
}
