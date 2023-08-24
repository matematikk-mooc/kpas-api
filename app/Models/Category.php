<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CourseCategory;


/**
 * @property int $id
 * @property string $category_name
 * @property int $position
 * @property string color_code
 */
class Category extends Model
{

    public $timestamps = false;
    protected $fillable = [
        'category_name',
        'position',
        'color_code',
    ];

    public function courseCategory()
    {
        return $this->hasMany(CourseCategory::class, 'category_id', 'id');
    }

}
