<?php

if (!function_exists('is_external_link')) {
    /**
     * Recognizes is the external link
     *
     * @param string url
     * @return bool
     */
    function is_external_link($url)
    {
        return strpos($url, config('app.url')) === FALSE;
    }
}

if (!function_exists('dataporten_api_uri')) {
    /**
     * Return trimmed dataporten uri
     *
     * @return string
     */
    function dataporten_api_uri($uri=null)
    {
        return trim(config('dataporten.api_url'), '/') . '/' . ($uri ? trim($uri, '/') : '');
    }
}

// ...
