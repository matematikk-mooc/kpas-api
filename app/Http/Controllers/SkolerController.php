<?php

namespace App\Http\Controllers;

use App\Barnehage;
use App\Fylke;
use App\Http\Responses\SuccessResponse;
use App\Kommune;
use App\Skole;
use Illuminate\Http\Request;

class SkolerController extends Controller
{
    /**
     * @return SuccessResponse
     */
    public function all_fylke()
    {
        $all_fylke = Fylke::all()->sortBy('Navn');
        return new SuccessResponse($all_fylke);
    }

    /**
     * @return SuccessResponse
     */
    public function all_kommune()
    {
        $all_kommune = Kommune::all()->sortBy('Navn');
        return new SuccessResponse($all_kommune);
    }

    /**
     * @return SuccessResponse
     */
    public function all_skole()
    {
        $skoler = Skole::where('ErSkole', true)->orderBy('Navn', 'ASC')->get();
        return new SuccessResponse($skoler);
    }

    /**
     * @return SuccessResponse
     */
    public function all_barnehage()
    {
        $all_barnehage = Barnehage::all()->sortBy('Navn');
        return new SuccessResponse($all_barnehage);
    }

    /**
     * Kommuner i Fylke
     * @param string $fylkesnr
     * @return SuccessResponse
     */
    public function kommuner(string $fylkesnr)
    {
        $kommuner = Kommune::where('Fylkesnr', $fylkesnr);
        return new SuccessResponse($kommuner->orderBy('Navn', 'ASC')->get());
    }

    /**
     * skoler i kommune
     *
     * @param string $kommunenr
     * @return SuccessResponse
     */
    public function skoler(string $kommunenr)
    {
        $skoler = Skole::where('ErSkole', true)->where("Kommunenr", $kommunenr);
        return new SuccessResponse($skoler->orderBy('Navn', 'ASC')->get());
    }

    /**
     * Barnehager i kommune
     *
     * @param string $kommunenr
     * @return SuccessResponse
     */
    public function barnehager(string $kommunenr)
    {
        $skoler = Barnehage::where("KommuneNr", $kommunenr);
        return new SuccessResponse($skoler->orderBy('Navn', 'ASC')->get());
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
}
