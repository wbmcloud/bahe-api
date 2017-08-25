<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;

class AuthController extends Controller
{
   public function jwtAction()
   {
       $key = env('JWT_SECRET');

       $token = array(
           "iss" => "account_center",
           "aud" => "game_client",
           "iat" => time(),
           "nbf" => time(),
       );

       return $this->jsonResponse([
           'jwt' => JWT::encode($token, $key)
       ]);
   }
}
