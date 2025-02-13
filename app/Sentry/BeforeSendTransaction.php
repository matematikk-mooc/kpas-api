<?php
namespace App\Sentry;

use Illuminate\Support\Facades\Log;
use Sentry\Event;

class BeforeSendTransaction
{
    public static function Filter(Event $transaction): ?Event
    {
        $contexts = $transaction->getContexts();
        $traceContext = isset($contexts['trace']) ? $contexts['trace'] : null;
        $traceStatus = isset($traceContext['status']) ? $traceContext['status'] : null;
        $transactionName = $transaction->getTransaction();

        $isTraceSuccess = $traceStatus === 'ok';
        $isPingRequest = strpos($transactionName, '/api/ping') !== false;

        if ($isTraceSuccess && $isPingRequest) return null;
        return $transaction;
    }
}
