<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        Config::set('auth.defaults.guard', 'users-api');
    }

    public function login(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        $token = auth()->attempt($validator);

        if (!$token) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }
    public function register(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'name' => 'required|string',
        ]);

        $user_alreadyExists = User::where(
            'email',
            '=',
            $request->get('email')
        )->first();

        if ($user_alreadyExists !== null) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'This user already exists',
                ],
                402
            );
        }
        $user = User::create(
            array_merge($validator, ['password' => bcrypt($request->password)])
        );

        return response()->json(
            [
                'message' => 'user created successfully',
                'user' => $user,
            ],
            201
        );
    }
    public function logout()
    {
        auth()->logout();
        return response()->json([
            'message' => 'Admin successfully loged out',
        ]);
    }
    public function userProfile()
    {
        return response()->json(auth()->user());
    }
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => strtotime(
                date('Y-m-d H:i:s'),
                strtotime('+60 min')
            ),
            'user' => auth()->user(),
        ]);
    }
}
