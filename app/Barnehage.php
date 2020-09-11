<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Barnehage extends Model
{
    //
    public $incrementing = false;
    protected $primaryKey = 'OrgNr';
    protected $fillable = ['KommuneNr', 'Navn', 'OrgNr', 'NSRId','FylkeNr', 'ErBarnehage', 'ErBarnehageEier', 'ErOffentligBarnehage','ErPrivatBarnehage'];
}
