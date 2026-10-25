<?php

namespace App\Http\Controllers;

use App\Utils\RemindersUtil;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class DitdataController extends Controller {
    public function reminders(Request $request): JsonResponse {
        try {
            $file = $request->query('file');
            $type = $request->query('type');
            if (!is_string($file) || $file === '') {
                throw new Exception('File parameter is required');
            }

            $auth = RemindersUtil::authenticateSharePoint($file);
            $buffer = RemindersUtil::downloadExcel($auth['url'], $auth['cookie']);

            $data = RemindersUtil::parseExcel($buffer);
            $tasks = RemindersUtil::transformToTasks($data);

            if ($type === 'alert') {
                $tasks = array_map(function ($task) {
                    $daysLeft = $task['days_left'] ?? 0;
                    $task['days_left'] = ($daysLeft === 0) ? 0 : (int) ceil($daysLeft / 7) * 7;
                    return $task;
                }, $tasks);
            }

            return response()->json($tasks, 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (Throwable $error) {
            $message = $error->getMessage() ?: 'Failed to process file';
            Log::error('Error: ' . $message, [
                'exception' => $error,
                'file' => $request->query('file'),
                'type' => $request->query('type'),
            ]);

            return response()->json(['error' => $message], 500, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
    }
}
