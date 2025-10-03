<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Badge extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'badge_name',
        'point',
        'badge_icon_url',
    ];

    /**
     * Get the user badges for the badge.
    */
    public function userBadges()
    {
        return $this->hasMany(UserBadge::class);
    }
}
