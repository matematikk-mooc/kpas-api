<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $canvas_id
 * @property string $name
 * @property string $description
 * @property int $category_id
 */
class Group extends Model
{
    protected $fillable = [
        'canvas_id',
        'name',
        'description',
        'category_id',
    ];

    public function users(){
        return $this->hasMany(JoinCanvasGroupUser::class, 'canvas_user_id', 'user_id');
    }
}
