<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AuthController;

Route::post('/api/login', [AuthController::class, 'login']);
Route::post('/api/logout', [AuthController::class, 'logout']);
Route::get('/api/auth/check', [AuthController::class, 'check']);
Route::put('/api/profile', [AuthController::class, 'updateProfile']);

use App\Http\Controllers\ApprovalRequestController;

Route::get('/api/employees/stats', [EmployeeController::class, 'stats']);
Route::post('/api/employees/bulk-delete', [EmployeeController::class, 'bulkDelete']);
Route::apiResource('/api/employees', EmployeeController::class);

Route::apiResource('/api/departments', App\Http\Controllers\DepartmentController::class);

Route::get('/api/approvals', [ApprovalRequestController::class, 'index']);
Route::post('/api/approvals', [ApprovalRequestController::class, 'store']);
Route::patch('/api/approvals/{id}', [ApprovalRequestController::class, 'update']);

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
