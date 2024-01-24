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