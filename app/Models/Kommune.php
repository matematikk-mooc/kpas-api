<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Fylke;

class Kommune extends Model
{
    public static $annetKommuneNavn = "Annen";
    public static $annetKommuneNr = '99';
    public $incrementing = false;
    protected $primaryKey = 'Kommunenr';
    protected $fillable = ['Fylkesnr', 'Navn', 'OrgNr', 'Kommunenr', 'ErNedlagt'];

    public function createAnnenKommune()
    {
        $key[$this->getKeyName()] = Kommune::$annetKommuneNr;
        Kommune::updateOrCreate($key, [
            'Fylkesnr' => Fylke::$annetFylkesNr,
            'Navn' => Kommune::$annetKommuneNavn,
            'OrgNr' => '999999999',
            'Kommunenr' => Kommune::$annetKommuneNr,
            'ErNedlagt' => false
        ]);

    }
    public function updateKommune($community)
    {
        $key[$this->getKeyName()] = $community[$this->getKeyName()];
        Kommune::updateOrCreate($key, $community);
    }
}
