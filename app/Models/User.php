<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'gender',
        'role',
        'birth_date',
        'gsm',
        'point',
        'location_id',
        'district_id', // Yeni eklenen alan
        'contact_info',
        'profile_photo_url',
        'is_active',
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
            'birth_date' => 'date',
        ];
    }

    /**
     * Get the location that the user belongs to.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the CVs for the user.
     */
    public function cvs()
    {
        return $this->hasMany(Cv::class);
    }

    /**
     * Get the user badges for the user.
     */
    public function userBadges()
    {
        return $this->hasMany(UserBadge::class);
    }

    /**
     * Get the certificates for the user.
     */
    public function userCertificates()
    {
        return $this->hasMany(UserCertificate::class);
    }

    /**
     * Get the logs for the user.
     */
    public function logs()
    {
        return $this->belongsToMany(Log::class, 'user_logs');
    }
    
    /**
     * Get the experiences for the user through CVs.
     */
    public function experiences()
    {
        return $this->hasManyThrough(Experience::class, Cv::class, 'user_id', 'cv_id');
    }

    /**
     * Get the educations for the user through CVs.
     */
    public function educations()
    {
        return $this->hasManyThrough(Education::class, Cv::class, 'user_id', 'cv_id');
    }

    /**
     * Get the abilities for the user through CVs.
     */
    public function abilities()
    {
        return $this->hasManyThrough(Ability::class, Cv::class, 'user_id', 'cv_id');
    }

    /**
     * Get the languages for the user through CVs.
     */
    public function languages()
    {
        return $this->hasManyThrough(Language::class, Cv::class, 'user_id', 'cv_id');
    }

}
