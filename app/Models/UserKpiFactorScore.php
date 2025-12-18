<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserKpiFactorScore extends Model
{
    protected $table = 'user_kpi_factor_scores';

    protected $fillable = [
        'user_kpi_period_id',
        'kpi_factor_id',
        'score',
        'note'
    ];

    public function kpiFactor()
    {
        return $this->belongsTo(KpiFactor::class, 'kpi_factor_id');
    }

    public function period()
    {
        return $this->belongsTo(UserKpiPeriod::class, 'user_kpi_period_id');
    }
}
