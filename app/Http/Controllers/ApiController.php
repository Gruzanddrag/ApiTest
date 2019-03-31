<?php

namespace App\Http\Controllers;

use Validator;
use App\Http\Middleware\isAuth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\User;

class ApiController extends Controller
{

    public function authUser(Request $request){
        $login = $request->all()['login'] ?? null;
        $pass = $request->all()['password'] ?? null;
        $token = isAuth::$userToken;
        $response = new JsonResponse();
        if($login == "admin" && $pass == "admin" ){
            $response->setJson(json_encode(array('status' => 'true', 'token' => $token)));
            return $response->setStatusCode(200, 'Successful authorization');
        }
        else{
            $response->setJson(json_encode(array('status' => 'false', 'message' => 'Invalid authorization data')));
            return $response->setStatusCode(401, 'Invalid authorization data');
        }
    }
}
