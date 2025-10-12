<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'abilities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cv_id',
        'abilities_name',
        'level',
        'description',
    ];

    /**
     * Get the CV that the ability belongs to.
     */
    public function cv()
    {
        return $this->belongsTo(Cv::class);
    }
}
