<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Trait\HttpResponses; // use trait

class AuthController extends Controller
{
    use HttpResponses; // use trait

    public function login() {
        return "ok";
    }

    public function register(StoreUserRequest $request) {
        $request->validated($request->all());

        // ...
    }

    public function logout() {
        return $this->success([
            "id" => 1
        ], "LOGOUT BERHASIL", 200);
    }
}
