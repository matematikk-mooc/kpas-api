<?php
$appEnv = env('APP_ENV', 'development');
$appVersion = env('APP_VERSION', '1.0.0-dev');
$sentryDSN = env('SENTRY_LARAVEL_DSN', env('SENTRY_DSN', ''));

if (!$appEnv != 'local' && $sentryDSN != '') {
    $sentryDSN = 'https://a01959126b295c4c067f7943636a04d3@o4507468577701888.ingest.de.sentry.io/4508453628084304';
}

/**
 * Sentry Laravel SDK configuration file.
 *
 * @see https://docs.sentry.io/platforms/php/guides/laravel/configuration/options/
 */
return [
    'dsn' => $sentryDSN,
    'spotlight' => env('SENTRY_SPOTLIGHT', false),
    'release' => $appVersion,
    'environment' => $appEnv,

    'sample_rate' => 1.0,
    'traces_sample_rate' => 1.0,
    'send_default_pii' => false,

    'before_send_transaction' => [\App\Sentry\BeforeSendTransaction::class, 'Filter'],

    'breadcrumbs' => [
        'logs' => env('SENTRY_BREADCRUMBS_LOGS_ENABLED', true),
        'cache' => env('SENTRY_BREADCRUMBS_CACHE_ENABLED', true),
        'livewire' => env('SENTRY_BREADCRUMBS_LIVEWIRE_ENABLED', true),
        'sql_queries' => env('SENTRY_BREADCRUMBS_SQL_QUERIES_ENABLED', true),
        'sql_bindings' => env('SENTRY_BREADCRUMBS_SQL_BINDINGS_ENABLED', false),
        'queue_info' => env('SENTRY_BREADCRUMBS_QUEUE_INFO_ENABLED', true),
        'command_info' => env('SENTRY_BREADCRUMBS_COMMAND_JOBS_ENABLED', true),
        'http_client_requests' => env('SENTRY_BREADCRUMBS_HTTP_CLIENT_REQUESTS_ENABLED', true),
        'notifications' => env('SENTRY_BREADCRUMBS_NOTIFICATIONS_ENABLED', true),
    ],

    'tracing' => [
        'queue_job_transactions' => env('SENTRY_TRACE_QUEUE_ENABLED', true),
        'queue_jobs' => env('SENTRY_TRACE_QUEUE_JOBS_ENABLED', true),
        'sql_queries' => env('SENTRY_TRACE_SQL_QUERIES_ENABLED', true),
        'sql_bindings' => env('SENTRY_TRACE_SQL_BINDINGS_ENABLED', false),
        'sql_origin' => env('SENTRY_TRACE_SQL_ORIGIN_ENABLED', true),
        'sql_origin_threshold_ms' => env('SENTRY_TRACE_SQL_ORIGIN_THRESHOLD_MS', 100),
        'views' => env('SENTRY_TRACE_VIEWS_ENABLED', true),
        'livewire' => env('SENTRY_TRACE_LIVEWIRE_ENABLED', true),
        'http_client_requests' => env('SENTRY_TRACE_HTTP_CLIENT_REQUESTS_ENABLED', true),
        'cache' => env('SENTRY_TRACE_CACHE_ENABLED', true),
        'redis_commands' => env('SENTRY_TRACE_REDIS_COMMANDS', true),
        'redis_origin' => env('SENTRY_TRACE_REDIS_ORIGIN_ENABLED', true),
        'notifications' => env('SENTRY_TRACE_NOTIFICATIONS_ENABLED', true),
        'missing_routes' => env('SENTRY_TRACE_MISSING_ROUTES_ENABLED', true),
        'continue_after_response' => env('SENTRY_TRACE_CONTINUE_AFTER_RESPONSE', true),
        'default_integrations' => env('SENTRY_TRACE_DEFAULT_INTEGRATIONS_ENABLED', true),
    ],
];
