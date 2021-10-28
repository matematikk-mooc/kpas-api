<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fylke extends Model
{
    public $incrementing = false;
    protected $primaryKey = 'Fylkesnr';
    protected $fillable = ['Fylkesnr', 'Navn', 'OrgNr', 'OrgNrFylkesmann'];

    public function UpdateFylke($county) {
        $key[$this->getKeyName()] = $county[$this->getKeyName()];
        Fylke::updateOrCreate($key, $county);
    }
}
