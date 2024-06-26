<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fylke extends Model
{
    public static $annetFylkesNavn = "Annet";
    public static $annetFylkesNr = '99';
    public $incrementing = false;
    protected $primaryKey = 'Fylkesnr';
    protected $fillable = ['Fylkesnr', 'Navn', 'OrgNr', 'OrgNrFylkesmann', 'nedlagt'];

    public function createAnnetFylke()
    {
        $annetOrgNr = '999999999';

        $key[$this->getKeyName()] = Fylke::$annetFylkesNr;
        Fylke::updateOrCreate($key, ['Fylkesnr' => Fylke::$annetFylkesNr, 'Navn' => Fylke::$annetFylkesNavn, 'OrgNr' => $annetOrgNr, 'OrgNrFylkesmann' => $annetOrgNr, 'nedlagt' => false]);

    }
    public function updateFylke($county)
    {
        if (!isset($county['nedlagt'])) {
            $county['nedlagt'] = false;
        }

        $key[$this->getKeyName()] = $county[$this->getKeyName()];
        Fylke::updateOrCreate($key, $county);
    }
}
