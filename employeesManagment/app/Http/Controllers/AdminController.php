<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use App\Models\Admin;
// use App\Models\Admin;

class AdminController extends Controller
{
    public function __construct()
    {
        Config::set('auth.defaults.guard', 'admin-api');
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

        $admin_alreadyExists = Admin::where(
            'email',
            '=',
            $request->get('email')
        )->first();

        if ($admin_alreadyExists !== null) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'This admin already exists',
                ],
                402
            );
        }
        $admin = Admin::create(
            array_merge($validator, ['password' => bcrypt($request->password)])
        );

        return response()->json(
            [
                'message' => 'Admin created successfully',
                'user' => $admin,
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
    public function getAllAdmins()
    {
        $admins = Admin::all();

        if ($admins->isEmpty()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'No admin found',
                ],
                404
            );
        }
        return response()->json(
            [
                'status' => false,
                'message' => 'Admins list',
                'data' => $admins,
            ],
            200
        );
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
