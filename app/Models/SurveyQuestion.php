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

    /**
     * A question where the responder can write a text answer
     *
     * HTML-element: <textarea>
     */
    public const QUESTION_TYPE_ESSAY = "essay";

    /**
     * A question where the responder chooses between five options, ranging from
     * "I svaert liten grad" to "I svaert stor grad", but also letting the user choose not to answer.
     *
     * HTML-elements: <input type="radio">
     */
    public const QUESTION_TYPE_LIKERT_SCALE_5_POINTS = "likert_scale_5pt";

    public static function getLikertScaleOptions(): array {
        return [
            "likert_scale_5pt_1" => "I svært liten grad",
            "likert_scale_5pt_2" => "I liten grad",
            "likert_scale_5pt_3" => "I hverken stor eller liten grad",
            "likert_scale_5pt_4" => "I stor grad",
            "likert_scale_5pt_5" => "I svært stor grad",
            "likert_scale_5pt_none" => "Vet ikke / Ønsker ikke svare"
        ];
    }


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

