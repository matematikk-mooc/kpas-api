<?php
namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;

class DiplomaController extends Controller
{
    public function logolist()
    {
        $images = array_diff(scandir("images"), array('.', '..', '.DS_Store'));
        logger("Images:" . print_r($images, true));
        foreach ($images as &$image) {
            logger("Image:" . $image);
        }
        return new SuccessResponse($images);
    }
}
