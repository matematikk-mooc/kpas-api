<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
