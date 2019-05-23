<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class SuccessResponse implements Responsable
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
        return response()->json([
            'status' => 200,
            'status_message' => 'Success',
            'result' => $this->data,
        ]);
    }
}
