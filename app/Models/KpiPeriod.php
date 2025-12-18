<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\MorphTo;

class KpiFactorScore extends Model
{
    public function user(): MorphTo
    {

        return $this->belongsTo(User::class, 'user_id');
    }
}
