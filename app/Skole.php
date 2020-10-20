<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skole extends Model
{
    //
    public $incrementing = false;
    protected $primaryKey = 'NSRId';
    protected $fillable = [
        'Kommunenr',
        'Navn',
        'FulltNavn',
        'OrgNr',
        'NSRId',
        'ErSkole',
        'ErSkoleEier',
        'ErGrunnSkole',
        'ErPrivatSkole',
        'ErOffentligSkole',
        'ErVideregaaendeSkole',
        'Breddegrad',
        'Lengdegrad',
    ];

    public function UpdateSkole($school) {
        $key[$this->getKeyName()] = $school[$this->getKeyName()];
        Skole::updateOrCreate($key, $school); 
    }
}
