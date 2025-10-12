<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'educations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cv_id',
        'school_name',
        'degree',
        'field_of_study',
        'start_date',
        'end_date',
        'description',
    ];

    /**
     * Get the CV that the education belongs to.
     */
    public function cv()
    {
        return $this->belongsTo(Cv::class);
    }
}
