<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class SuccessXmlResponse implements Responsable
{

    /**
     * @var string
     */
    protected $data;

    public function __construct($data)
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
        return response($this->data, 200)->header('Content-Type', 'text/xml');;
    }
}
