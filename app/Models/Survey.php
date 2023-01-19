<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $course_id
 * @property string|null $title_form
 * @property string $title_internal
 */
class Survey extends Model
{
    public $timestamps = false;

    protected $nullable = ['title_form'];
    protected $fillable = [
        'course_id',
        'title_form',
        'title_internal',
        'created',
        'deleted'
    ];

    public function submissions()
    {
        return $this->hasMany(SurveySubmission::class, 'survey_id', 'id');
    }

    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class, 'survey_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function($model)
        {
            self::setNullables($model);
        });
    }

    protected static function setNullables($model)
    {
        foreach($model->nullable as $field)
        {
            if(empty($model->{$field}))
            {
                $model->{$field} = null;
            }
        }
    }
}
