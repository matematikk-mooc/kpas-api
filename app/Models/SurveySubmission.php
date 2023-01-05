<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveySubmission extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'survey_id', 
        'user_id',
        'submitted',
        'deleted'
    ];
}