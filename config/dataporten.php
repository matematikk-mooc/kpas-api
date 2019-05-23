<?php
return [
    'api_url' => env('DATAPORTEN_API_URL', 'https://auth.dataporten.no'),
    'client_id' => env('DATAPORTEN_CLIENT_ID', ''),
    'client_secret' => env('DATAPORTEN_SECRET', ''),
    'redirect_uri' => env('DATAPORTEN_REDIRECT_URI', ''),
    'debug' => env('DATAPORTEN_DEBUG', false),
];
