<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'languages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cv_id',
        'language_name',
        'level',
        'description',
    ];

    /**
     * Get the CV that the language belongs to.
     */
    public function cv()
    {
        return $this->belongsTo(Cv::class);
    }
}
