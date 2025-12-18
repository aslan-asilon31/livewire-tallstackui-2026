<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserDetail extends Model
{
    protected $table = 'user_details';

    protected $guarded = [];


    public function user(): MorphTo
    {

        return $this->belongsTo(User::class, 'user_id');
    }

    protected $casts = [
        'birth_date' => 'date', // otomatis jadi Carbon
    ];
}
