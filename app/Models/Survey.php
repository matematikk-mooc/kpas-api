<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $fillable = [
        'course_id',
        'title_form',
        'title_internal',
        'created',
        'deleted'
    ];
}