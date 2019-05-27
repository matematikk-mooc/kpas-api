<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class ErrorResponse implements Responsable
{
    /**
     * @var string
     */
    protected $data;
    /**
     * @var int
     */
    protected $code;

    public function __construct(string $data, int $code = 400)
    {
        $this->data = $data;
        $this->code = $code;
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
            'status' => $this->code,
            'status_message' => 'Failure',
            'result' => $this->data
        ]);
    }
}
