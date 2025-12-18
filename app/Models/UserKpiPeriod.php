<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserKpiPeriod extends Model
{
    protected $table = 'user_kpi_periods';

    protected $fillable = [
        'user_id',
        'kpi_id',
        'year',
        'month',
        'quarter',
        'final_score'
    ];

    public function kpi()
    {
        return $this->belongsTo(Kpi::class, 'kpi_id');
    }

    public function factorScores()
    {
        return $this->hasMany(UserKpiFactorScore::class, 'user_kpi_period_id');
    }
}
