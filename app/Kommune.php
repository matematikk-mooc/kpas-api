<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kommune extends Model
{
    //
    public $incrementing = false;
    protected $primaryKey = 'Kommunenr';
    protected $fillable = ['Fylkesnr', 'Navn', 'OrgNr', 'Kommunenr', 'ErNedlagt'];

    public function UpdateKommune($community) {
        $key[$this->getKeyName()] = $community[$this->getKeyName()];
        Kommune::updateOrCreate($key, $community);
    }
}
