<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kommune extends Model
{
    //
    public $incrementing = false;
    protected $primaryKey = 'Kommunenr';
    protected $fillable = ['Fylkesnr', 'Navn', 'OrgNr', 'Kommunenr'];
}
