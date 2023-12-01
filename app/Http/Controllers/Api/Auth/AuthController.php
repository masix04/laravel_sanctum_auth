<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

use App\Models\User;
use App\Models\FcmToken;
use App\Models\UsersFcmToken;
use App\Models\PersonalAccessToken;

use App\Repositories\AuthRepository;

class AuthController extends Controller
{
    protected $authRepo;

    public function __construct(AuthRepository $auth)
    {
        $this->authRepo = $auth;
    }

    public function register(Request $request)
    {
        // Validation
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'dob' => 'required',
                'password' => 'required|string|min:8',
                'fcm_token' => 'required|string'
            ]
        );

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()->all()
            ];
            return response()->json($response, 400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $success['token'] = $user->createToken('Api Token - S')->plainTextToken;
        $success['name'] = $user->name;

        $this->saveFcmToken($request);

        $response = [
            'success' => true,
            'data' => $success,
            'message' => 'User register successfully'
        ];

        return response()->json($response, 200);
    }

    public function login(Request $request)
    {
        $user = $this->authRepo->checkUserExistance($request)->refresh();

        // Delete all previous tokens for user
        PersonalAccessToken::where('tokenable_id', $user->id)->delete();

        if (Auth::attempt(['email' =>  $request->email, 'password' => $request->password])) {

            $success = $this->verifyAccount($request);

            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'User login successfully'
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => 'Authorization Failed! Please make sure to add correct credentials!'
            ];
            return response()->json($response);
        }
    }

    public function verifyAccount(Request $request)
    {
        $user = $this->authRepo->makeUserVerified($request);
        return $this->generateToken($request, $user);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->tokens();
        $token->delete();

        $response = ['message' => 'You have been successfully logged out!'];

        return response()->json($response, 200);
    }

    public function generateToken($request, $user)
    {
        $user->tokens()->delete();

        FcmToken::updateOrCreate([
            'token' => $request->token,
        ], [
            'device_type' =>  ($request->device? $request->device: $request->header('Device-Type'))
        ]);

        UsersFcmToken::updateOrCreate([
            'user_id' => $user->id,
            'fcm_token' => $request->token
        ]);

        return [
            'user' => $user,
            'token' => $user->createToken('appApiToken')->plainTextToken
        ];
    }

    private function saveFcmToken($request)
    {
        FcmToken::updateOrCreate([
            'token' => $request->fcm_token,
        ], [
            'device_type' =>  ($request->device? $request->device: $request->header('Device-Type'))
        ]);
    }
}
