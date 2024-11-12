<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Http\Responses\ErrorResponse;
use App\Http\Responses\SuccessResponse;
use App\Services\CanvasService;
use App\Repositories\CourseSettingsRepository;

class ActivityStatisticsController extends Controller
{
    /**
     * @var CanvasService
     */
    protected $canvasService;

    public function __construct(CanvasService $canvasService)
    {
        $this->canvasService = $canvasService;
    }

    public function getCourseActivity(string $courseId) {
        try {
            $cleanCourseId = (int) $courseId;
            if ($cleanCourseId <= 0) return new ErrorResponse([ 'error' => 'Invalid course id', 'results' => [] ]);

            $results = [];
            $enrollments = $this->canvasService->getCourseEnrollments($cleanCourseId);
            foreach ($enrollments as $enrollmentItem) {
                $userId = isset($enrollmentItem->user_id) ? (int) $enrollmentItem->user_id : 0;
                $userType = isset($enrollmentItem->type) ? $enrollmentItem->type : '';
                $totalActivityTime = isset($enrollmentItem->total_activity_time) ? (int) $enrollmentItem->total_activity_time : 0;

                if ($userId <= 0 || $totalActivityTime <= 0 || $userType !== 'StudentEnrollment') continue;
                if (!isset($results[$userId])) $results[$userId] = [ 'user_id' => $userId, 'activity_time_secounds' => 0 ];

                $results[$userId]['activity_time_secounds'] += $totalActivityTime;
            }

            $activityTimes = array_column($results, 'activity_time_secounds');
            $mean = array_sum($activityTimes) / count($activityTimes);
            $stdDev = sqrt(array_sum(array_map(fn($time) => pow($time - $mean, 2), $activityTimes)) / count($activityTimes));

            sort($activityTimes);
            $median = $this->calculateMedian($activityTimes);
            $lowerQuartile = $this->calculateQuartile($activityTimes, 0.25);
            $upperQuartile = $this->calculateQuartile($activityTimes, 0.75);
            $min = min($activityTimes);
            $max = max($activityTimes);

            return new SuccessResponse([
                'error' => false,
                'results' => [
                    'participantsCount' => count($results),
                    'statistics' => [
                        'mean' => $mean,
                        'stdDev' => $stdDev,
                        'median' => $median,
                        'lowerQuartile' => $lowerQuartile,
                        'upperQuartile' => $upperQuartile,
                        'min' => $min,
                        'max' => $max
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return new ErrorResponse($e->getMessage());
        }
    }

    private function calculateMedian(array $values): float {
        $count = count($values);
        $middleIndex = (int) floor($count / 2);
        if ($count % 2) return $values[$middleIndex];
    
        return ($values[$middleIndex - 1] + $values[$middleIndex]) / 2;
    }
    private function calculateQuartile(array $values, float $percentile): float {
        $position = ($percentile * (count($values) - 1));
        $base = (int) floor($position);
        $remainder = $position - $base;
        if (isset($values[$base + 1])) return $values[$base] + $remainder * ($values[$base + 1] - $values[$base]);

        return $values[$base];
    }
}
