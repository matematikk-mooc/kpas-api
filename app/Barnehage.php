<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Barnehage extends Model
{
    //
    public $incrementing = false;
    protected $primaryKey = 'OrgNr';
    protected $fillable = [
        'KommuneNr',
        'Navn',
        'FulltNavn',
        'OrgNr',
        'NSRId',
        'FylkeNr',
        'ErBarnehage',
        'ErBarnehageEier',
        'ErOffentligBarnehage',
        'ErPrivatBarnehage',
        'Breddegrad',
        'Lengdegrad',
    ];

    public function UpdateBarnehage($kindergarten) {
        $key[$this->getKeyName()] = $kindergarten[$this->getKeyName()];
        Barnehage::updateOrCreate($key, $kindergarten);
    }
}
