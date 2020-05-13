<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use App\LTI\CustomToolProvider;
use App\Repositories\CanvasDbRepository;
use Illuminate\Support\Facades\DB;
use IMSGlobal\LTI\ToolProvider\DataConnector\DataConnector;

class CourseController extends Controller
{

    /**
     * @var CanvasDbRepository
     */
    private $canvasDbRepository;
    /**
     * @var CustomToolProvider
     */
    private $toolProvider;

    public function __construct(CanvasDbRepository $canvasDbRepository)
    {
        $this->canvasDbRepository = $canvasDbRepository;
        $db = DB::connection()->getPdo();
        $dataConnector = DataConnector::getDataConnector('', $db, 'pdo');
        $this->toolProvider = new CustomToolProvider($dataConnector);
    }

    public function index(int $courseId): SuccessResponse
    {
        $externalTools = $this->canvasDbRepository->getExternalToolsByCourseId($courseId);
        $consumers = $this->toolProvider->getConsumers();
        $consumerKeys = [];

        foreach ($consumers as $consumer){
            $consumerKey = $consumer->getKey();
            if (isset($consumerKey)) {
                array_push($consumerKeys, $consumerKey);
            }
        }

        foreach($externalTools as $externalTool){
            $externalToolKey = $externalTool->consumer_key;
            if (in_array($externalToolKey, $consumerKeys)){
                return new SuccessResponse(true);
            }
        }
        return new SuccessResponse(false);
    }
}
