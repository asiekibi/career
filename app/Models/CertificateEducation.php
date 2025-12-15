<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateEducation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'certificate_educations';

    protected $fillable = [
        'certificate_id',
        'course_name',
        'puanlar'
    ];

    /**
     * Get the certificate that owns this education.
     */
    public function certificate()
    {
        return $this->belongsTo(Certificate::class);
    }

    /**
     * Get the certificate lessons (scores) for this education.
     */
    public function certificateLessons()
    {
        return $this->hasMany(CertificateLesson::class);
    }
}
