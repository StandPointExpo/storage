<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Exception;

class CrmUserController extends Controller
{

    /**
    * From request give token and get auth user from auth server
    * @param Request
    * @return String
    * 
    */
    public function getAuthToken (Request $request) {
        $token = $request->bearerToken();
        if(!$token) {
            return false;
        }
        return $token;
    }

    /**
    * From request give token and get auth user from auth server
    * @param token String
    * @return Collection
    */
    public function getAuthUser ($token) :Collection {
        $response = Http::withToken($token)->get(config('app.auth_server') . '/api/auth/user');
        return $response->collect();
    }


    /**
     * [setAuthUser description]
     *
     * @param   Request  $request  [$request description]
     * @param   [type]   $request  [$request description]
     * @param   [type]   $token    [$token description]
     * @param   [type]   $token    [$token description]
     *
     * @return  [type]             [return description]
     */
    public function setAuthUser(Request $request) {
        $token = $authUser->getAuthToken($request);
        if($token) {
            $authUser->getAuthUser($token);
        }
    }
}
