<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Middleware\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * Security: The use of wildcard here can be permitted as long as we make sure
     * all requests to the web application originates from Azure Front Door. This is 
     * currently done by using Azure Private Link for communication between AFD and web app.
     *
     * @var array
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     * 
     * We're currently using Azure Front Door as proxy. The selection of headers done here is
     * based on their documentation: 
     * https://learn.microsoft.com/en-us/azure/frontdoor/front-door-http-headers-protocol
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_FOR |
    Request::HEADER_X_FORWARDED_HOST |
    Request::HEADER_X_FORWARDED_PROTO;
}
