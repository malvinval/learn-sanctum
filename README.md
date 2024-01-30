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

# Membuat Controller

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

# Setup Postman

Silahkan setup Postman sendiri ya. Bikin workspace, collection, dan lain-lainnya.

# Routing

Ketika membuat API di Laravel, idealnya kita routing didalam file `routes/api.php`. Nantinya setiap route yang merupakan bagian dari API, akan memiliki prefix `/api`. Misal ada route login maka endpointnya adalah `/api/login`.

Silahkan buka file `routes/api.php`, dan kita buat 1 route POST:

```php
Route::post("/login", [AuthController::class, "login"]);
```

Dari kode diatas, pahami bahwa kita akan menggunakan method `login` dalam controller `AuthController`. Kita tidak perlu menuliskan prefix `/api` sebagai endpoint karena sudah otomatis dilakukan.

# Jalankan Server

Jalankan command `php artisan serve`.

# Testing via Postman

Silahkan send POST request melalui Postman ke address server Laravel. Ingat, endpointnya diawali dengan `/api`.

# Membuat Response dari Controller

Sebelumnya, kita sudah membuat trait sebagai response dari API kita. Gunakan trait tersebut dalam controller kita. Berikut contohnya:

```php
namespace App\Http\Controllers;

use App\Trait\HttpResponses; // use trait
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use HttpResponses; // use trait

    public function register() {
        return $this->success([
            "id" => 1
        ], "REGISTER BERHASIL", 200);
    }
}
```

Note: `$this` mengacu pada class `AuthController`. `success()` adalah method yang kita gunakan dari trait `HttpResponses`.

# Membuat Controller Task

Sebenernya ini bebas sih, ga harus `TaskController`. Dalam contoh ini, `TaskController` adalah sebuah resource.
Jalankan command `php artisan make:controller TaskController -r`. Kemudian buat routenya di file `routes/api.php`:

```php
Route::resource("/tasks", TaskController::class);
```

# Implementasi API Register

1. Kita buat sebuah Request dengan menjalankan command `php artisan make:request StoreUserRequest`. Maka akan dibuatkan sebuah folder bernama `Requests` dalam folder `app/Http`, dan seluruh file Request akan disimpan dalam folder `Requests` tersebut.

2. Apa gunanya file tersebut? Untuk membuat rules (aturan) validasi request yang diberikan user. Implementasikan kodenya dalam method `rules()`:

```php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => ["required", "string", "max:255"],
            "email" => ["required", "string", "max:255", "unique:users"],
            "password" => ["required", "confirmed", Password::defaults()],
        ];
    }
}
```

Note: **Jangan lupa return `true` dalam method `authorize()`**

3. Lakukan validasi di method `register()` dalam `AuthController`:

```php
public function register(StoreUserRequest $request) {
    $request->validated($request->all());

    // ...
}
```

4. Setelah validasi berhasil, create data kedalam database sekaligus membuatkan token untuk user yang terdaftar. Token tersebut nantinya juga akan disertakan dalam response API kita.

```php
public function register(StoreUserRequest $request) {
    $request->validated($request->all());

    $user = User::create([
        "name" => $request->name,
        "email" => $request->email,
        "password" => Hash::make($request->password)
    ]);

    return $this->success([
        "user" => $user,
        "token" => $user->createToken("API Token of " . $user->name)->plainTextToken
    ], "Registered!", 200);
}
```

Note: `createToken()` berguna untuk generate token kepada user, dan properti `plainTextToken` berguna untuk memberikan plain token dalam API response, bukan dalam bentuk hashed token.

Note: Argumen dalam method `createToken` dibutuhkan untuk memberikan nama token dalam database.

5. Testing Request API via Postman

Gunakan kedua request header berikut:

`Accept`: `application/json`

`Content-Type`: `application/json`

Selain itu, kirimkan data `name`, `email`, `password`, dan `password_confirmation` melalui request body raw.

```
{
    "name": "Malvin Valerian",
    "email": "malvin@gmail.com",
    "password": "12345678",
    "password_confirmation": "12345678"
}
```

`password_confirmation` digunakan karena kita menggunakan rules `Password::defaults()`.

Setelah berhasil melakukan request via Postman, API kita akan memberikan response:

```
{
    "status": "OK!",
    "message": "Registered!",
    "data": {
        "user": {
            "name": "Malvin Valerian",
            "email": "malvinval@gmail.com",
            "updated_at": "2024-01-30T16:03:31.000000Z",
            "created_at": "2024-01-30T16:03:31.000000Z",
            "id": 1
        },
        "token": "1|JCozWksb2U0bGv5Ufizl7GEJtEFZ4r2lmTFcPrHz5ff9af85"
    }
}
```

Token dari user tersebut akan disimpan dalam table `personal_access_tokens` di database kita.