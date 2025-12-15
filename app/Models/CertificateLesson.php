<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateLesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_certificate_id',
        'certificate_education_id',
        'score',
    ];

    /**
     * Get the user certificate that owns this lesson score.
     */
    public function userCertificate()
    {
        return $this->belongsTo(UserCertificate::class);
    }

    /**
     * Get the certificate education (course) for this lesson score.
     */
    public function certificateEducation()
    {
        return $this->belongsTo(CertificateEducation::class);
    }
}
