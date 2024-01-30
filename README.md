# Instalasi

Jalankan command `composer require laravel/sanctum`

# Publish file konfigurasi dan migration

Jalankan command `php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"`

# Setup database

Jangan lupa konfigurasi file `.env`

# Trait

Idealnya, API akan memberikan response kepada tiap request yang diterima. Kita bisa membuat traits untuk API Response yang bersifat reusable. Jadi kita gaperlu nulis kode untuk response yang sama berkali-kali.

1. Buat folder bernama `Trait` dalam folder `app` project Laravel
2. Buat file bernama `HttpResponses.php` (nama file bebas)
3. Coding trait:

```php
<?php

// definisikan namespace sesuai folder
namespace App\Trait;

// nama trait disamakan dengan nama file
trait HttpResponse {
    protected function success($data, $message = null, $statusCode = 200) {
        return response()->json([
            "status" => "OK!",
            "message" => $message,
            "data" => $data
        ], $statusCode); // status code as second argument
    }

    protected function error($data, $message = null, $statusCode) {
        return response()->json([
            "status" => "ERROR!",
            "message" => $message,
            "data" => $data
        ], $statusCode); // status code as second argument
    }
}
```

4. Membuat controller

Sekarang kita bikin controller `AuthController` dengan command `php artisan make:controller AuthController`, dan menggunakan trait `HttpResponses` didalam controller tersebut.

```php
<?php

namespace App\Http\Controllers;

use App\Trait\HttpResponses; // use trait
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use HttpResponses; // use trait
}
```

5. Setup Postman

Silahkan setup Postman sendiri ya. Bikin workspace, collection, dan lain-lainnya.

6. Routing

Ketika membuat API di Laravel, idealnya kita routing didalam file `routes/api.php`. Nantinya setiap route yang merupakan bagian dari API, akan memiliki prefix `/api`. Misal ada route login maka endpointnya adalah `/api/login`.

Silahkan buka file `routes/api.php`, dan kita buat 1 route POST:

```php
Route::post("/login", [AuthController::class, "login"]);
```

Dari kode diatas, pahami bahwa kita akan menggunakan method `login` dalam controller `AuthController`. Kita tidak perlu menuliskan prefix `/api` sebagai endpoint karena sudah otomatis dilakukan.

7. Jalankan Server

Jalankan command `php artisan serve`.

8. Testing via Postman

Silahkan send POST request melalui Postman ke address server Laravel. Ingat, endpointnya diawali dengan `/api`.