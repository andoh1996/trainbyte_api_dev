<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/////////////////Public route////////////////////////
Route::post('/auth/register', [App\Http\Controllers\AuthController::class, 'registerUser']);
Route::post('/auth/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/auth/resend-verification', [App\Http\Controllers\AuthController::class, 'resendVerificationEmail']);
Route::get('/auth/verify-email', [App\Http\Controllers\AuthController::class, 'verifyEmail']);




////////////Protected route/////////////////////////
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/profile', [App\Http\Controllers\AuthController::class, 'profile']);
    // courses endpoints
    Route::get('/courses', [App\Http\Controllers\CoursesController::class, 'getCourses']);
    Route::get('/courses/list/{id}', [App\Http\Controllers\CoursesController::class, 'getOneCourse']);

    // Admin-only endpoints
    Route::middleware('role:admin')->group(function () {
        ///////////Courses////
        Route::post('/courses/create', [App\Http\Controllers\CoursesController::class, 'createCourse']);
        Route::patch('/courses/{id}', [App\Http\Controllers\CoursesController::class, 'updateCourse']);
        Route::delete('/courses/{id}', [App\Http\Controllers\CoursesController::class, 'deleteCourse']);
    });
});

