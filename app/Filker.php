<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Filker extends Model
{
    //
    public $incrementing = false;
    protected $primaryKey = 'Fylkesnr';
    protected $fillable = ['Fylkesnr', 'Navn', 'OrgNr', 'OrgNrFylkesmann'];
}
