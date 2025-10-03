<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cv extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'resume',
        'hobbies',
    ];

    /**
     * Get the user that owns the CV.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the abilities for the CV.
     */
    public function abilities()
    {
        return $this->hasMany(Ability::class);
    }

    /**
     * Get the experiences for the CV.
     */
    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    /**
     * Get the educations for the CV.
     */
    public function educations()
    {
        return $this->hasMany(Education::class);
    }

    /**
     * Get the languages for the CV.
     */
    public function languages()
    {
        return $this->hasMany(Language::class);
    }
}
