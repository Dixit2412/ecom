<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiBaseController;
use App\Models\User;
use Validator;

class AuthController extends ApiBaseController
{
    /**
     * Login Req
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($data)) {
            if (empty(auth()->user()->shop_id)) {
                return response()->json(['status' => false, 'msg' => 'Requested shop detail not found in our system or may be deactivate by admin.', 'code' => 403]);
            }
            $token = auth()->user()->createToken('e_com-passport-auth')->accessToken;
            $success['token'] =  $token;
            $success['name'] =  auth()->user()->name;
            return $this->sendResponse($success, 'User login successfully.');
        }
        return response()->json(['error' => 'Unauthorised'], 401);
    }

    public function userInfo()
    {

        $user = auth()->user();

        return response()->json(['user' => $user], 200);
    }

    public function logOut()
    {
        $tokenRepository = app('Laravel\Passport\TokenRepository');

        $user = auth('api')->user();

        if ($user) {
            $tokenRepository->revokeAccessToken($user->token()->id);
            return $this->sendResponse([], 'User logout successfully.');
        }
        return $this->sendResponse([], 'User already logout.');
    }
}
