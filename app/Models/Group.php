<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'canvas_id',
        'name',
        'description',
        'category_id',
    ];
}
