<?php


namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client as OClient;

use App\User;

class AuthService implements AuthServiceInterface
{
    private $userModel;
    private $oauthEndpoint;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
        $this->oauthEndpoint = env("APP_URL", "").":".env("APP_PORT", "");
    }

    public function doLogin(string $email, string $password) {
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $accessToken = auth()->user()->createToken('authToken')->accessToken;
            return ['isSuccess'=>true, 'message'=>['accessToken' => $accessToken]];
        }
        else {
            return false;
        }
    }

    public function doRegister(string $name,string $email, string $password) {
        $newUser = [
            'name' => $name,
            'email' => $email,
            'password' => \bcrypt($password)
        ];

        try {
            $this->userModel::create($newUser);
        } catch (Throwable $e) {
            report($e);

            return ['isSuccess'=>false, 'message'=>'cannot create user'];
        }

        return ['isSuccess'=>true, 'message'=>'user was successfully created'];
    }

    private function generateTokens(OClient $oClient,string $email, string $password) {
        $http = new Client;
        $response = $http->request('POST', "$this->oauthEndpoint/oauth/token", [
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
