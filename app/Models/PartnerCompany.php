<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_person',
        'birth_date',
        'phone',
        'email',
        'company_name',
        'tax_office',
        'tax_number',
        'message',
        'has_permission',
        'status'
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];
}
