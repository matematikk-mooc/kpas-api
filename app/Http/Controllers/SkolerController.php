<?php

namespace App\Http\Controllers;

use App\Models\Barnehage;
use App\Models\Fylke;
use App\Http\Responses\SuccessResponse;
use App\Models\Kommune;
use App\Models\Skole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

function format_return_data($data)
{
    return collect($data)
        ->sortBy(function ($enhet, $key) {
            if($enhet["Navn"] == Fylke::$annetFylkesNavn || $enhet["Navn"] == Kommune::$annetKommuneNavn || $enhet["Navn"] == Skole::$annetSkoleNavn || $enhet["Navn"] == Barnehage::$annetBarnehageNavn ) {
                return "";
            }

            return $enhet['Navn'];
        })
        ->values()
        ->toArray();
}

class SkolerController extends Controller
{
    /**
     * @return SuccessResponse
     */
    public function all_fylke()
    {
        $all_fylke = Fylke::where('nedlagt', false)->get();
        return new SuccessResponse(format_return_data($all_fylke));
    }

    /**
     * @return SuccessResponse
     */
    public function all_kommune()
    {
        $all_kommune = Kommune::all()->sortBy('Navn');
        return new SuccessResponse(format_return_data($all_kommune));
    }

    /**
     * @return SuccessResponse
     */
    public function all_skole()
    {
        if ( isset($_GET['only_high_schools']) ) {
            $onlyHighSchools = filter_var($_GET['only_high_schools'], FILTER_VALIDATE_BOOLEAN);
        } else{
            $onlyHighSchools = false;
        }
        if ($onlyHighSchools) {
            $skoler = Skole::where('ErSkole', true)->where('ErVideregaaendeSkole', true)->orderBy('Navn', 'ASC')->get();
        } else {
            $skoler = Skole::where('ErSkole', true)->orderBy('FulltNavn', 'ASC')->get();
        }

        return new SuccessResponse(format_return_data($skoler));
    }

    /**
     * @return SuccessResponse
     */
    public function all_barnehage()
    {
        $all_barnehage = Barnehage::where("ErBarnehage", true)->orderBy('FulltNavn', 'ASC')->get();
        return new SuccessResponse(format_return_data($all_barnehage));
    }

    /**
     * Fylke
     * @param string $fylkesnr
     * @return SuccessResponse
     */
    public function fylke(string $fylkesnr)
    {
        $fylke = Fylke::where('Fylkesnr', $fylkesnr)->first();
        $fylke = format_return_data(array($fylke));
        return new SuccessResponse($fylke[0]);
    }

    /**
     * Kommune
     * @param string $kommunenr
     * @return SuccessResponse
     */
    public function kommune(string $kommunenr)
    {
        $kommune = Kommune::where('Kommunenr', $kommunenr)->first();
        $kommune = format_return_data(array($kommune));
        return new SuccessResponse($kommune[0]);
    }

    /**
     * Kommuner i Fylke
     * @param string $fylkesnr
     * @return SuccessResponse
     */
    public function kommuner(string $fylkesnr)
    {
        $kommuner = Kommune::where('ErNedlagt', false)->where('Fylkesnr', $fylkesnr)->orWhere('Fylkesnr', Fylke::$annetFylkesNr)->get();
        $kommuner = format_return_data($kommuner);
        return new SuccessResponse($kommuner);
    }

    /**
     * skoler i kommune
     *
     * @param string $kommunenr
     * @return SuccessResponse
     */
    public function skoler_by_community(string $kommunenr)
    {
        $skoler = Skole::where('ErSkole', true)->where("Kommunenr", $kommunenr)->orWhere('Kommunenr', Kommune::$annetKommuneNr)->get();
        $skoler = format_return_data($skoler);
        return new SuccessResponse($skoler);
    }

    /**
     * skoler i fylke
     *
     * @param string $fylkesNr
     * @return SuccessResponse
     */
    public function skoler_by_county(string $fylkesNr)
    {
        $kommuner = Kommune::where('ErNedlagt', false)->where('Fylkesnr', $fylkesNr)->orWhere('Fylkesnr', Fylke::$annetFylkesNr)->get();
        $all_schools_for_county = array();

        foreach ($kommuner as $kommune) {
            $kommunenr = $kommune->Kommunenr;
            $skoler = Skole::where('ErSkole', true)->where("Kommunenr", $kommunenr);
            $skoler = $skoler->where(function ($query) {
                $query->where('ErGrunnskole', true)->orWhere('ErVideregaaendeSkole', true);
            })->get();
            $skoler = format_return_data($skoler);
            $all_schools_for_county = array_merge($all_schools_for_county, $skoler);
        }

        return new SuccessResponse($all_schools_for_county);
    }

    /**
     * Barnehager i kommune
     *
     * @param string $kommunenr
     * @return SuccessResponse
     */
    public function barnehager(string $kommunenr)
    {
        $skoler = Barnehage::where("KommuneNr", $kommunenr)->orWhere('Kommunenr', Kommune::$annetKommuneNr)->where("ErBarnehage", true)->get();
        $skoler = format_return_data($skoler);
        return new SuccessResponse($skoler);
    }

    /**
     *  Post Fylke
     * @param Request $request
     * @return SuccessResponse
     */
    public function store_fylke(Request $request)
    {
        return new SuccessResponse(Fylke::updateOrCreate($request->all())->get());
    }

    /**
     *  Post Kommune
     * @param Request $request
     * @return SuccessResponse
     */
    public function store_kommune(Request $request)
    {
        return new SuccessResponse(Kommune::updateOrCreate($request->all())->get());

    }

    /**
     *  Post Skole
     * @param Request $request
     * @return SuccessResponse
     */
    public function store_skole(Request $request)
    {
        return new SuccessResponse(Skole::updateOrCreate($request->all())->get());

    }

    /**
     *  Post Barnehage
     * @param Request $request
     * @return SuccessResponse
     */
    public function store_barnehage(Request $request)
    {
        return new SuccessResponse(Barnehage::updateOrCreate($request->all())->get());

    }

    /**
     * run scheduler fetching data from nsr
     * @param Request $request
     * @return string
     */
    public function run_scheduler(Request $request)
    {
        Artisan::call('schedule:run');
        return 'OK';
    }
}
