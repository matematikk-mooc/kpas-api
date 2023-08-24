<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;


/**
 * @property int $course_id
 * @property int $category_id
 * @property bool $new
 * @property int $position
 */
class CourseCategory extends Model
{

    public $timestamps = false;
    public $incrementing = false;

    protected $primaryKey = [
        'course_id',
        'category_id',
    ];
    protected $fillable = [
        'course_id',
        'category_id',
        'new',
        'position'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    // public function courseSettings()
    // {
    //     return $this->belongsTo(CourseSettings::class, 'course_id', 'course_id');
    // }


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
    *
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
