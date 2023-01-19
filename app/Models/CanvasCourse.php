<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $canvas_id
 * @property string $name
 */
class CanvasCourse extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'canvas_id',
        'name',
    ];

}
