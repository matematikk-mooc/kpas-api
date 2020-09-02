<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EnrollmentActivity extends Model
{
    //
    protected $fillable = ['course_id', 'course_name','active_users_count', 'activity_date'];
}
