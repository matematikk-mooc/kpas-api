<?php
return [
    'api_url' => env('DATAPORTEN_API_URL', 'https://api.dataporten.no'),
    'auth_api_url' => env('DATAPORTEN_AUTH_API_URL', 'https://auth.dataporten.no'),
    'groups_api_url' => env('DATAPORTEN_GROUPS_API_URL', 'https://groups-api.dataporten.no'),
    'client_id' => env('DATAPORTEN_CLIENT_ID', ''),
    'client_secret' => env('DATAPORTEN_SECRET', ''),
    'redirect_uri' => env('DATAPORTEN_REDIRECT_URI', ''),
    'debug' => env('DATAPORTEN_DEBUG', false),
];
