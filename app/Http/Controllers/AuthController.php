<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Trait\HttpResponses; // use trait
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses; // use trait

    public function login() {
        return "ok";
    }

    public function register(StoreUserRequest $request) {
        $request->validated($request->all());

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        return $this->success([
            "user" => $user,
            "token" => $user->createToken("Token of " . $user->name)->plainTextToken
        ], "Registered!", 200);
    }

    public function logout() {
        return $this->success([
            "id" => 1
        ], "LOGOUT BERHASIL", 200);
    }
}
