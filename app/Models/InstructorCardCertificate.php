<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructorCardCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'instructor_card_request_id',
        'user_certificate_id',
    ];

    /**
     * Get the instructor card request.
     */
    public function instructorCardRequest()
    {
        return $this->belongsTo(InstructorCardRequest::class, 'instructor_card_request_id');
    }

    /**
     * Get the user certificate.
     */
    public function userCertificate()
    {
        return $this->belongsTo(UserCertificate::class, 'user_certificate_id');
    }
}

