<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */


    // App\Models\User.php

    public function detail()
    {
        return $this->hasOne(\App\Models\UserDetail::class, 'user_id');
    }

    public function kpiPeriods()
    {
        return $this->hasMany(\App\Models\UserKpiPeriod::class, 'user_id');
    }

    public function files(): MorphMany
    {

        return $this->morphMany(File::class, 'fileable');
    }

    public function avatar()
    {
        return $this->morphOne(\App\Models\File::class, 'fileable')
            ->where('type', 'avatar')
            ->where('is_activated', 1);
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_activated',
        'queue_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
