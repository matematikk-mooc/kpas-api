<?php

namespace App\Http\Controllers;

use App\Exceptions\CanvasException;
use App\Http\Responses\ErrorResponse;
use App\Http\Responses\SuccessResponse;
use App\Services\CanvasService;
use Illuminate\Contracts\Support\Responsable;

class AccountController extends Controller
{

    /**
     * @var CanvasService
     */
    protected $canvasService;

    public function __construct(CanvasService $canvasService)
    {
        $this->canvasService = $canvasService;
    }

    public function getPermissions(string $accountId): Responsable {
        try {
            return new SuccessResponse($this->canvasService->getAccountPermissions($accountId));
        } catch (Exeption $e) {
            return new ErrorResponse($e->getMessage());
        }
    }
}