<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBadge extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'badge_id',
    ];

    /**
     * Get the user that owns the badge.
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the badge.
    */
    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }
}
