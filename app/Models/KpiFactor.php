<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpiFactor extends Model
{
    protected $table = 'kpi_factors';

    protected $fillable = [
        'kpi_id',
        'name',
        'code',
        'weight',
        'definition'
    ];

    public function kpi()
    {
        return $this->belongsTo(Kpi::class, 'kpi_id');
    }
}
