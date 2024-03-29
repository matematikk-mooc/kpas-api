<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $question_id
 * @property int $submission_id
 * @property string $value
 */
class SurveySubmissionData extends Model
{
    protected $primaryKey = [
        'question_id',
        'submission_id',
    ];
    public $incrementing = false;
    public $timestamps = false;
    public function submission()
    {
        return $this->belongsTo(SurveySubmission::class);
    }

    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class);
    }

    protected $fillable = ['question_id', 'submission_id', 'value'];

    /**
    * Override methods for composite primary keys
    *
    * Set the keys for a save update query.
    *
    * @param  \Illuminate\Database\Eloquent\Builder  $query
    * @return \Illuminate\Database\Eloquent\Builder
    */
    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if(!is_array($keys)){
            return parent::setKeysForSaveQuery($query);
        }

        foreach($keys as $keyName){
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    /**
    * Get the primary key value for a save query.
    *
    * @param mixed $keyName
    * @return mixed
    */
    protected function getKeyForSaveQuery($keyName = null)
    {
        if(is_null($keyName)){
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }
}
