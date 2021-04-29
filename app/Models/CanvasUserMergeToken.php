<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CanvasUserMergeToken extends Model
{
    /**
     * Canvas user id of the user to merge from, e.g. the user that will be removed after the merge.
     */
    protected $primaryKey = 'canvas_user_id';

    protected $fillable = ['canvas_user_id'];

    // Primary key is not auto-incrementing
    public $incrementing = false;
}
