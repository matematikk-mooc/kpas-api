<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Fylke;
use App\Kommune;

class Barnehage extends Model
{
    public static $annetBarnehageNavn = "Annen";
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

    public function CreateAnnenBarnehage() {
        $annenNsrId = '99';
        $key[$this->getKeyName()] = $annenNsrId;
        Skole::updateOrCreate($key, 
        [
            'Kommunenr' => '99', 
            'Navn' => Barnehage::$annetBarnehageNavn, 
            'FulltNavn' => Barnehage::$annetBarnehageNavn,
            'OrgNr' => '999999999', 
            'NSRId' => $annenNsrId,
            'FylkeNr' => Fylke::$annetFylkesNr,
            'ErBarnehage' => true,
            'ErBarnehageEier' => false,
            'ErOffentligBarnehage' => true,
            'ErPrivatBarnehage' => false,
            'Breddegrad' => "61.84264",
            'Lengdegrad' => "10.23966",
        ]);

    }

    public function UpdateBarnehage($kindergarten) {
        $key[$this->getKeyName()] = $kindergarten[$this->getKeyName()];
        Barnehage::updateOrCreate($key, $kindergarten);
    }
}
