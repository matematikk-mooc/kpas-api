<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CanvasCourse extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'canvas_id',
        'name',
    ];

    public function surveys()
    {
        return $this->hasMany(Survey::class, 'course_id', 'canvas_id');
    }

}
