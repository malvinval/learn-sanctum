<?php

namespace App\Http\Controllers;

use App\Trait\HttpResponses; // use trait
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use HttpResponses; // use trait

    public function login() {
        return "ok";
    }

    public function register() {
        return $this->success([
            "id" => 1
        ], "REGISTER BERHASIL", 200);
    }
}
