<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('auth/jwt', 'AuthController@jwtAction');

$app->group(['middleware' => ['validator', 'auth']], function () use ($app) {
    $app->get('wechat/userinfo', 'WechatController@userInfoAction');
    $app->get('maintain/server', 'MaintainController@serverAction');
    $app->post('maintain/server', 'MaintainController@serverAction');
});
