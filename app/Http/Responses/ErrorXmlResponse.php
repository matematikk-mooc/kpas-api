<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class ErrorXmlResponse implements Responsable
{
    /**
     * @var string
     */
    protected $data;
    /**
     * @var int
     */
    protected $code;

    public function __construct(string $data)
    {
        $this->data = $data;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        $errorXml = "<error>" .$this->data . "</error>";
        return response($errorXml, 200)->header('Content-Type', 'text/xml');;
    }
}
