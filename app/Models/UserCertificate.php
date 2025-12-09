<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCertificate extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'certificate_id',
        'user_id',
        'certificate_code',
        'register_no',
        'password',
        'content1',
        'content2',
        'achievement_score',
        'issuing_institution',
        'acquisition_date',
        'validity_period',
        'success_score',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'acquisition_date' => 'date',
            'achievement_score' => 'integer',
            'success_score' => 'integer',
        ];
    }

    /**
     * Get the user that owns the certificate.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the certificate.
     */
    public function certificate()
    {
        return $this->belongsTo(Certificate::class);
    }
}
