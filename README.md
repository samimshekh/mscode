# mscode
A lightweight yet advanced PHP web framework by Samim Shekh. Designed for speed, clean architecture, and modern routing. Perfect for building scalable and structured web applications.
## Routing System

final public static function get(string $url, string $Processors, ?string $Guard = null);
final public static function group($function, ?string $Guards = NULL);
```php
use Settings\Route;

Route::group(function () {
    Route::get("/", "Home::index");
    Route::get("/(int id=userid::id)a(int id=userid::id)", "Home::home", "Home::index");
}, "Group::index");
