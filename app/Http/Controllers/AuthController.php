<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public $loginAfterSignUp = true;

    public function login(Request $request)
    {
        $credentials = $request->only("email", "password");
        $token = null;

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                "status" => false,
                "message" => "Unauthorized"
            ]);
        }

        return response()->json([
            "status" => true,
            "token" => $token
        ]);
    }

    public function registervalidate(Request $request)
    {
        $this->validate($request, [
            "name" => "required|string",
            "email" => "required|email|unique:users",
            "password" => "required|string|min:6|max:10"
        ]);

        $user = User::where('isregister', '!=', '1')->where('usercode', $request->usercode)->first();
        if($user){
            $user->name = $request->name;
            $user->surname = $request->surname;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->isregister = 1;
            $user->isadmin = 0;
            $user->save();
        }
        else{
            return response()->json([
                "status" => false,
                "message" => "User Code not found"
            ]);
        }

        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }

        return response()->json([
            "status" => true,
            "user" => $user
        ]);
    }

    public function register(Request $request)
    {
        try {
            $user = new User();
            $user->usercode = $request->usercode;
            $user->isregister = 0;
            $user->isadmin = 0;
            $user->save();

            return response()->json([
                "status" => true,
                "user" => $user
            ]);
        } catch (Exception $e) {
            report($e);

            return response()->json([
                "status" => false,
                "error" => $e
            ]);
        }

    }

    public function logout(Request $request)
    {
        $this->validate($request, [
            "token" => "required"
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                "status" => true,
                "message" => "User logged out successfully"
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                "status" => false,
                "message" => "Ops, the user can not be logged out"
            ]);
        }
    }
}
