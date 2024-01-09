<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Fylke;
use App\Models\Kommune;

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
        $annenOrgNr = '999999999';
        $key[$this->getKeyName()] = $annenOrgNr;
        Barnehage::updateOrCreate($key,
        [
            'KommuneNr' => Kommune::$annetKommuneNr,
            'Navn' => Barnehage::$annetBarnehageNavn,
            'FulltNavn' => Barnehage::$annetBarnehageNavn,
            'OrgNr' => $annenOrgNr,
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
