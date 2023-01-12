<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    public function course()
    {
        return $this->belongsTo(CanvasCourse::class, 'course_id', 'canvas_id');
    }

    public function submissions()
    {
        return $this->hasMany(SurveySubmission::class, 'survey_id', 'id');
    }

    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class, 'survey_id', 'id');
    }
}
