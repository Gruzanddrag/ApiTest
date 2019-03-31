<?php

namespace App\Http\Middleware;

use Validator;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class isAuth
{
    public static $userToken = "03739B69410B83A49EC9629A64A53B4F";
    public static $messages = [
        'required'    =>  ':attribute is empty',
    ];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();
        $isComments= $request->is("*comments");
        if(($token != isAuth::$userToken || $token == null || $token == "") && !$isComments){
            $response = new JsonResponse();
            $response->setJson( json_encode( array( 'message' => 'Unauthorized') ) );
            $response->setStatusCode(401, 'Unauthorized');
            return $response;
        }
        return $next($request);
    }
}
