<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\User;

class AuthApiController extends Controller
{

    public function authUser(Request $request){
        $login = $request->input('login');
        $pass = $request->input('password');
        $token = "LUPAZALUPA";
        $response = new JsonResponse();
        if($login == "admin" && $pass == "admin"){
            $response->setJson(json_encode(array('status' => 'true', 'token' => $token)));
            return $response->setStatusCode(200, 'Successful authorization');
        }
        else{
            $response->setJson(json_encode(array('status' => 'false', 'message' => 'Invalid authorization data')));
            return $response->setStatusCode(401, 'Invalid authorization data');
        }
    }
}
