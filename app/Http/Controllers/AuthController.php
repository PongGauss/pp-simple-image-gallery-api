<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthServiceInterface;
use Validator;

class AuthController extends BaseApiController
{
    private $authService;
    public function __construct(AuthServiceInterface $authService) {
        $this->authService = $authService;
    }
    //
    public $successStatus = 200;
    public $noAuthoStatus = 401;
    public $appErrorStatus = 400;

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], $this->noAuthoStatus);
        }

        $resp = $this->authService->doLogin($request->email, $request->password);

        if( !$resp['isSuccess'] ) {
            return $this->response(['error' => 'Unauthorised'], $this->noAuthoStatus);
        }
        return $this->response($resp['message'], $this->successStatus);
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->response(['error'=>$validator->errors()], $this->appErrorStatus);
        }

        $resp = $this->authService->doRegister($request->name, $request->email, $request->password);
        if( !$resp['isSuccess'] ) {
            return $this->response($resp['message'], $this->noAuthoStatus);
        }
        return $this->response($resp['message'], $this->successStatus);
    }

    public function getTokenAndRefreshToken(OClient $oClient, $email, $password) {
        $oClient = OClient::where('password_client', 1)->first();
        $http = new Client;
        $response = $http->request('POST', 'http://mylemp-nginx/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'username' => $email,
                'password' => $password,
                'scope' => '*',
            ],
        ]);

        $result = json_decode((string) $response->getBody(), true);
        return response()->json($result, $this->successStatus);
    }
}
