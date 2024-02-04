<?php

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PatientController;
use App\Http\Controllers\API\StatusUpdateController;
use App\Http\Controllers\API\Admin\AuthController;
use Illuminate\Support\Facades\Route;
//patient
//Authenticated routes

// Route::get('test', function () {
//     return response()->json([
//         'message' => 'suucees'
//     ]);
// })->middleware('auth:api');


Route::post('login', [AuthController::class, 'login']);

Route::middleware(['Authenticate:user-api', 'CheckUserToken:Admin'])->group(function () {

    Route::get('Admin/UsersList', [UserController::class, 'index']);
    //name instead of id
    Route::get('Admin/SingleUser/{Name}', [UserController::class, 'show']);
    Route::post('Admin/CreateUser', [UserController::class, 'store']);
    Route::put('Admin/UpdateUserInfo/{id}', [UserController::class, 'update']);
    Route::delete('Admin/DeleteUser/{id}', [UserController::class, 'destroy']);
    Route::put('Admin/UpdatePatientForm/{id}', [PatientController::class, 'update']);
    Route::delete('Admin/DeletePatientForm/{id}', [PatientController::class, 'destroy']);
    Route::delete('Admin/DeleteStatusUpdateForm/{id}', [StatusUpdateController::class, 'destroy']);

    Route::middleware(['Authenticate:user-api', 'CheckUserToken:Admin,Hospital'])->group(function () {
        Route::get('Admin/PatientFormsList', [PatientController::class, 'index']);
        Route::get('Admin/SinglePatientForm/{id}', [PatientController::class, 'show']);
        Route::get('Admin/StatusUpdateFormsList', [StatusUpdateController::class, 'index']);
        Route::get('Admin/SingleStatusUpdateForm/{id}', [StatusUpdateController::class, 'show']);
    });
});

Route::middleware(['Authenticate:user-api', 'CheckUserToken:Paramedic'])->group(function () {
    Route::post('CreatePatientForm', [PatientController::class, 'store']);
    Route::post('CreateStatusUpdateForm', [StatusUpdateController::class, 'store']);
});

Route::post('logout', [AuthController::class, 'logout']);


// Route::get('UpdateStatusUpdateForm/{id}', [StatusUpdateController::class, 'update']);
Route::group(['middleware' => 'Authenticate:user-api'], function () {
    Route::post('profile', function () {
        return Auth::user();
    });
});
