<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $course_id
 * @property string $title_form
 * @property string $title_internal
 * @property bool $deleted
 */
class Survey extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'course_id',
        'title_form',
        'title_internal',
        'created',
        'deleted'
    ];
}

