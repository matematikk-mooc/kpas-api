<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @property int $id
 * @property int $course_id
 * @property \date|null $unmaintained_since
 * @property string|null $banner_text
 * @property string $banner_type
 * @property bool $licence
 * @property bool $role_support
 * @property string $multilang
 */
class CourseSettings extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'course_id',
        'unmaintained_since',
        'role_support',
        'licence',
        'multilang',
        'banner_type',
        'banner_text',
        'image_id'
    ];
    protected $nullable = ['unmaintained_since', 'banner_text'];

    public function courseCategory()
    {
        return $this->hasOne(CourseCategory::class, 'course_id', 'course_id');
    }

    public function image()
    {
        return $this->hasOne(CourseImage::class, 'id', 'image_id');
    }

    public function courseFilter()
    {
        return $this->hasMany(CourseFilter::class, 'course_id', 'course_id');
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
