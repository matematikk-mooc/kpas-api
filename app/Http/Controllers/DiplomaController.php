<?php
namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;

class DiplomaController extends Controller
{
    public function logolist()
    {
        logger("DiplomaController::logolist");
        $images = array_diff(scandir("images"), array('.', '..', '.DS_Store'));
        return new SuccessResponse($images);
    }
}
