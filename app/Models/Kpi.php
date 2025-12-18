<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    protected $table = 'kpis';

    protected $fillable = ['name', 'description'];

    public function factors()
    {
        return $this->hasMany(KpiFactorScore::class, 'kpi_id');
    }
}
