<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subtitles extends Model
{
    protected $primaryKey = 'videoId';

    public $incrementing = false;
}
