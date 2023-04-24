<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $canvas_id
 * @property string $name
 * @property string $description
 * @property int $category_id
 * @property int $course_id
 * @property int $county_id
 * @property int $community_id
 */
class Group extends Model
{

    protected $nullable = ['course_id', 'county_id', 'community_id'];
    protected $fillable = [
        'canvas_id',
        'name',
        'course_id',
        'county_id',
        'community_id',
        'description',
        'category_id',
    ];

    public function users(){
        return $this->hasMany(JoinCanvasGroupUser::class, 'canvas_user_id', 'user_id');
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
