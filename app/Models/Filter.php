<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $filter_name
 */
class Filters extends Model
{

    public $timestamps = false;
    protected $fillable = [
        'filter_name',
    ];

    public function courseFilter()
    {
        return $this->hasMany(CourseFilter::class, 'filter_id', 'id');
    }

}
