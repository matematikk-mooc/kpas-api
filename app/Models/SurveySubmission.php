<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $survey_id
 * @property int $user_id
 */
class SurveySubmission extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'survey_id',
        'user_id',
        'submitted',
        'deleted'
    ];
    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

}
