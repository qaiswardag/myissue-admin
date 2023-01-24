<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/", function () {
    return Inertia::render("Home/Home", [
        "canLogin" => Route::has("login"),
        "canRegister" => Route::has("register"),
        "laravelVersion" => Application::VERSION,
        "phpVersion" => PHP_VERSION,
    ]);
})->name("home");

//
// middleware for group of pages
Route::middleware([
    "auth:sanctum",
    config("jetstream.auth_session"),
    "verified",
])
    // group of pages
    ->group(function () {
        // just for testing. Delete Testing.vue when this route is deleted
        Route::get("/test", function () {
            return Inertia::render("Testing");
        })->name("test");
        // dashboard
        Route::get("/dashboard", function () {
            return Inertia::render("Dashboard/Dashboard");
        })->name("dashboard");
        // privacy policy
        Route::get("/privacy-policy", function () {
            return Inertia::render("PolicyAndTerms/PrivacyPolicy");
        })->name("privacyPolicy");
    });

// for testing
// for testing
// for testing
// just for testing. Delete Testing.vue when this route is deleted
Route::get("/test", function () {
    return Inertia::render("Testing", [
        "currentTime" => now()->toTimeString(),
    ]);
})->name("test");
// test post request

// for testing
// for testing
// for testing
