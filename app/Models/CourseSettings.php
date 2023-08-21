<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @property int $id
 * @property int $course_id
 * @property date|null $unmaintained_since
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
    ];
    protected $nullable = ['unmaintained_since', 'banner_text'];


}
