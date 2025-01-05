<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|min:3|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:4|max:191',
            'role' => 'string'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'user'
        ]);
        $token= $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'data' => $user
        ], 201);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|min:3|max:191',
            'password' => 'required|string|min:4|max:191',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        if(!Auth::attempt($request->only('name', 'password'))){
            return response()->json([
                'message' => 'Nama dan password tidak cocok'
            ], 401);
        }
        try {
            $user = User::where('name', $request->name)->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'access_token' => $token,
                'data' => $user
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Nama tidak ditemukan',
                'error' => $th->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id){
        $user = User::findOrFail($id);
        $validator = Validator::make($request->all(),[
            'name' => 'string|min:3|max:191',
            'email' => 'string|email|max:191|unique:users,email,'.$id,
            'password' => 'string|min:4|max:191',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        if($request->password){
            $request->merge([
                'password' => Hash::make($request->password)
            ]);
        }
        $user->update($request->all());
        return response()->json($user);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Token berhasil dihapus'
        ], 200);
    }
}
