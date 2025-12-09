<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'surname',
        'email',
        'company_name',
        'tax_number',
        'tax_office',
        'phone',
        'address',
        'message',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Get the user who approved this request.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
