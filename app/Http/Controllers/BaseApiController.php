<?php

namespace App\Http\Controllers;

class BaseApiController extends Controller
{
    protected function response($message, int $responseStatus) {
        return response()->json(['message'=>$message], $responseStatus);
    }
}
