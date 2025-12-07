<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructorCardRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'instructor_name',
        'email',
        'phone',
        'status',
        'notes',
        'request_count',
        'is_excluded_from_count',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get the user that owns the request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the certificates for the instructor card request.
     */
    public function certificates()
    {
        return $this->hasMany(InstructorCardCertificate::class, 'instructor_card_request_id');
    }
}

