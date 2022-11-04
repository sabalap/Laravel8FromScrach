<?php

use App\Http\Controllers\AdminPostController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PostCommentsController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionsController;
use App\Services\Newsletter;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

Route::post("newsletter",function(Newsletter $newsletter) {
    request()->validate(["email" => "required|email"]);
    
try {
    $newsletter->subscribe(request("email"));
} catch (\Exception $e) {
    throw ValidationException::withMessages([
        "email" => "This email could not be added to our newsletter list."
    ]);
}

return redirect("/")->with("success","You are now signed up for our newsletter!");
});

Route::get('/', [PostController::class,"index"])->name("home");

Route::get("posts/{post:slug}",[PostController::class,"show"]);
Route::post("posts/{post:slug}/comments",[PostCommentsController::class,"store"]);

Route::post("newsletter",NewsletterController::class);

Route::get("register",[RegisterController::class,"create"])->middleware("guest");
Route::post("register",[RegisterController::class,"store"])->middleware("guest");

Route::get("login",[SessionsController::class,"create"])->middleware("guest");
Route::post("login",[SessionsController::class,"store"])->middleware("guest");

Route::post("logout",[SessionsController::class,"destroy"])->middleware("auth");


Route::post("admin/posts",[AdminPostController::class,"store"])->middleware("admin");
Route::get("admin/posts/create",[AdminPostController::class,"create"])->middleware("admin");
Route::get("admin/posts",[AdminPostController::class,"index"])->middleware("admin");
Route::get("admin/posts/{post}/edit",[AdminPostController::class,"edit"])->middleware("admin");
Route::patch("admin/posts/{post}",[AdminPostController::class,"update"])->middleware("admin");
Route::delete("admin/posts/{post}",[AdminPostController::class,"destroy"])->middleware("admin");
