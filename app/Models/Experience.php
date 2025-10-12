<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cv_id',
        'company_name',
        'position',
        'start_date',
        'end_date',
        'description',
    ];

    /**
     * Get the CV that the experience belongs to.
     */
    public function cv()
    {
        return $this->belongsTo(Cv::class);
    }
}
