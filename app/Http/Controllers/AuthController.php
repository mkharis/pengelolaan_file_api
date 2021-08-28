<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['nama', 'password']);;

        if (! $token = app('auth')->attempt($credentials)) {
            return response()->json(['eror' => 'Tidak terotorisasi'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(app('auth')->user());
    }

    public function logout()
    {
        app('auth')->logout();

        return response()->json(['pesan' => 'Berhasil keluar']);
    }

    public function refresh()
    {
        return $this->respondWithToken(app('auth')->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'tipe_token' => 'bearer',
            'kadaluarsa' => app('auth')->factory()->getTTL() * 60
        ]);
    }

}
