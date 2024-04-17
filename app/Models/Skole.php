<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kommune;

class Skole extends Model
{
    public static $annetSkoleNavn = "Annen";
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

    public function createAnnenSkole() {
        $annenNsrId = '99';
        $key[$this->getKeyName()] = $annenNsrId;
        Skole::updateOrCreate($key,
        [
            'Kommunenr' => Kommune::$annetKommuneNr,
            'Navn' => Skole::$annetSkoleNavn,
            'FulltNavn' => Skole::$annetSkoleNavn,
            'OrgNr' => '999999999',
            'NSRId' => $annenNsrId,
            'ErSkole' => true,
            'ErSkoleEier' => false,
            'ErGrunnSkole' => true,
            'ErPrivatSkole' => false,
            'ErOffentligSkole' => true,
            'ErVideregaaendeSkole' => false,
            'Breddegrad' => "61.84264",
            'Lengdegrad' => "10.23966",
        ]);
    }

    public function updateSkole($school) {
        $key[$this->getKeyName()] = $school[$this->getKeyName()];
        Skole::updateOrCreate($key, $school);
    }
}
