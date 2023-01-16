<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\EmailController;

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

//company routes
Route::post('/add_company', [CompanyController::class, 'store']);
Route::get('/companies', [CompanyController::class, 'index']);
Route::get('/company/{id}', [CompanyController::class, 'getOneCompany']);
Route::delete('/company/{id}', [CompanyController::class, 'deleteCompany']);
Route::put('/company/{id}', [CompanyController::class, 'updateCompany']);
Route::post('/company/{name}', [CompanyController::class, 'searchCompany']);
Route::post('/companies/sort', [CompanyController::class, 'sortCompanyByName']);

//email route
Route::post('/sendemail', [EmailController::class, 'sendEmail']);

//incitation routes
Route::post('/invitation', [InvitationController::class, 'addInvitation']);
Route::get('/invitations', [InvitationController::class, 'getAllInvitations']);
Route::delete('/invitation/{id}', [
    InvitationController::class,
    'deleteCompany',
]);
Route::put('/invitation/{id}', [
    InvitationController::class,
    'updateInvitation',
]);

//admin routes
Route::group(['prefix' => '/admin'], function ($route) {
    Route::post('/login', [AdminController::class, 'login']);
    Route::post('/register', [AdminController::class, 'register']);
});

Route::group(
    ['middleware' => ['jwt.role:admin' => 'jwt.auth'], 'prefix' => '/admin'],
    function ($route) {
        Route::get('/user-profile', [AdminController::class, 'userProfile']);
        Route::get('/admins', [AdminController::class, 'getAllAdmins']);
        Route::post('/logout', [AdminController::class, 'logout']);
    }
);

//user routes
Route::group(['prefix' => '/user'], function ($route) {
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);
});

Route::group(['middleware' => ['jwt.role:user' => 'jwt.auth']], function (
    $route
) {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/user-profile', [UserController::class, 'userProfile']);
});
