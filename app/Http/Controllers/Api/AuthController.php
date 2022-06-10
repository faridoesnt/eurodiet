<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // rules validasi data
        $validator = Validator::make($request->all(), [
            'username'  => 'required|string|unique:users',
            'password'  => 'required|string|min:8'
        ]);

        // cek validasi jika gagal
        if($validator->fails())
        {
            return response()->json($validator->errors(), 401);
        }

        // membuat user
        $user   = User::create([
            'username'  => $request->username,
            'password'  => Hash::make($request->password) // hashing password
        ]);

        // membuat token
        $token  = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'       => 'Berhasil Mendaftar',
            'data'          => $user,
            'access_token'  => $token,
            'token_type'    => 'Bearer Token'
        ], 200);
    }

    public function login(Request $request)
    {
        if(!Auth::attempt($request->only('username', 'password')))
        {
            return response()->json([
                'message' => 'Username atau Password tidak ditemukan.'
            ], 401);
        }

        $user   = User::where('username', $request['username'])->firstOrFail();

        $token  = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'       => 'Berhasil Masuk',
            'data'          => $user,
            'access_token'  => $token,
            'token_type'    => 'Bearer Token'
        ], 200);
    }

    public function logout()
    {
        // hapus token user yang sedang login
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Berhasil logout dan token sudah di hapus.'
        ];
    }
}
