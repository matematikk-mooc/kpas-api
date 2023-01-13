<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $survey_id
 * @property string $machine_name
 * @property string $text
 * @property string $question_type
 * @property bool $required
 */
class SurveyQuestion extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'survey_id',
        'machine_name',
        'text',
        'question_type',
        'required',
        'deleted'
    ];
    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function submissionData()
    {
        return $this->hasMany(SurveySubmissionData::class, 'question_id', 'id');
    }

}

