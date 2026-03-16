<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\EmployeeContractController;
use App\Http\Controllers\Api\LeaveRequestController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ClientContractController;
use App\Http\Controllers\Api\SiteController;
use App\Http\Controllers\Api\AreaController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TaskLogController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\InventoryItemController;
use App\Http\Controllers\Api\SiteInventoryController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\InvoicePlanController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('profile', [AuthController::class, 'profile']);
        Route::put('profile', [AuthController::class, 'updateProfile']);
        Route::put('password', [AuthController::class, 'changePassword']);
    });

    // User info
    Route::get('user', function (Request $request) {
        return $request->user()->load(['employee', 'roles', 'permissions']);
    });

    // User Management
    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store']);
    Route::get('users/{user}', [UserController::class, 'show']);
    Route::put('users/{user}', [UserController::class, 'update']);
    Route::patch('users/{user}', [UserController::class, 'update']);
    Route::delete('users/{user}', [UserController::class, 'destroy']);

    // Role Management
    Route::get('roles', [RoleController::class, 'index']);
    Route::post('roles', [RoleController::class, 'store']);
    Route::get('roles/{role}', [RoleController::class, 'show']);
    Route::put('roles/{role}', [RoleController::class, 'update']);
    Route::patch('roles/{role}', [RoleController::class, 'update']);
    Route::delete('roles/{role}', [RoleController::class, 'destroy']);

    // Permission Management
    Route::get('permissions', [PermissionController::class, 'index']);

    // Employee Management
    Route::get('employees', [EmployeeController::class, 'index']);
    Route::post('employees', [EmployeeController::class, 'store']);
    Route::get('employees/{employee}', [EmployeeController::class, 'show']);
    Route::put('employees/{employee}', [EmployeeController::class, 'update']);
    Route::patch('employees/{employee}', [EmployeeController::class, 'update']);
    Route::delete('employees/{employee}', [EmployeeController::class, 'destroy']);

    // Employee Contracts
    Route::get('employee-contracts', [EmployeeContractController::class, 'index']);
    Route::post('employee-contracts', [EmployeeContractController::class, 'store']);
    Route::get('employee-contracts/{employee_contract}', [EmployeeContractController::class, 'show']);
    Route::put('employee-contracts/{employee_contract}', [EmployeeContractController::class, 'update']);
    Route::patch('employee-contracts/{employee_contract}', [EmployeeContractController::class, 'update']);
    Route::delete('employee-contracts/{employee_contract}', [EmployeeContractController::class, 'destroy']);

    // Leave Requests
    Route::get('leave-requests', [LeaveRequestController::class, 'index']);
    Route::post('leave-requests', [LeaveRequestController::class, 'store']);
    Route::get('leave-requests/{leave_request}', [LeaveRequestController::class, 'show']);
    Route::put('leave-requests/{leave_request}', [LeaveRequestController::class, 'update']);
    Route::patch('leave-requests/{leave_request}', [LeaveRequestController::class, 'update']);
    Route::delete('leave-requests/{leave_request}', [LeaveRequestController::class, 'destroy']);
    Route::post('leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])
        ->name('leave-requests.approve');

    // Client Management
    Route::get('clients', [ClientController::class, 'index']);
    Route::post('clients', [ClientController::class, 'store']);
    Route::get('clients/{client}', [ClientController::class, 'show']);
    Route::put('clients/{client}', [ClientController::class, 'update']);
    Route::patch('clients/{client}', [ClientController::class, 'update']);
    Route::delete('clients/{client}', [ClientController::class, 'destroy']);

    // Client Contracts
    Route::get('client-contracts', [ClientContractController::class, 'index']);
    Route::post('client-contracts', [ClientContractController::class, 'store']);
    Route::get('client-contracts/{client_contract}', [ClientContractController::class, 'show']);
    Route::put('client-contracts/{client_contract}', [ClientContractController::class, 'update']);
    Route::patch('client-contracts/{client_contract}', [ClientContractController::class, 'update']);
    Route::delete('client-contracts/{client_contract}', [ClientContractController::class, 'destroy']);

    // Site Management
    Route::get('sites', [SiteController::class, 'index']);
    Route::post('sites', [SiteController::class, 'store']);
    Route::get('sites/{site}', [SiteController::class, 'show']);
    Route::put('sites/{site}', [SiteController::class, 'update']);
    Route::patch('sites/{site}', [SiteController::class, 'update']);
    Route::delete('sites/{site}', [SiteController::class, 'destroy']);

    // Area Management
    Route::get('areas', [AreaController::class, 'index']);
    Route::post('areas', [AreaController::class, 'store']);
    Route::get('areas/{area}', [AreaController::class, 'show']);
    Route::put('areas/{area}', [AreaController::class, 'update']);
    Route::patch('areas/{area}', [AreaController::class, 'update']);
    Route::delete('areas/{area}', [AreaController::class, 'destroy']);

    // Task Management
    Route::get('tasks', [TaskController::class, 'index']);
    Route::post('tasks', [TaskController::class, 'store']);
    Route::get('tasks/{task}', [TaskController::class, 'show']);
    Route::put('tasks/{task}', [TaskController::class, 'update']);
    Route::patch('tasks/{task}', [TaskController::class, 'update']);
    Route::delete('tasks/{task}', [TaskController::class, 'destroy']);
    Route::post('tasks/{task}/assign', [TaskController::class, 'assign'])
        ->name('tasks.assign');

    // Task Logs
    Route::get('task-logs', [TaskLogController::class, 'index']);
    Route::post('task-logs', [TaskLogController::class, 'store']);
    Route::get('task-logs/{task_log}', [TaskLogController::class, 'show']);
    Route::delete('task-logs/{task_log}', [TaskLogController::class, 'destroy']);

    // Attendance Management
    Route::get('attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::get('attendances/{attendance}', [AttendanceController::class, 'show'])->name('attendances.show');
    Route::post('attendances/clock-in', [AttendanceController::class, 'clockIn'])->name('attendances.clock-in');
    Route::post('attendances/clock-out', [AttendanceController::class, 'clockOut'])->name('attendances.clock-out');

    // Inventory Items
    Route::get('inventory-items', [InventoryItemController::class, 'index']);
    Route::post('inventory-items', [InventoryItemController::class, 'store']);
    Route::get('inventory-items/{inventory_item}', [InventoryItemController::class, 'show']);
    Route::put('inventory-items/{inventory_item}', [InventoryItemController::class, 'update']);
    Route::patch('inventory-items/{inventory_item}', [InventoryItemController::class, 'update']);
    Route::delete('inventory-items/{inventory_item}', [InventoryItemController::class, 'destroy']);

    // Site Inventories
    Route::get('site-inventories', [SiteInventoryController::class, 'index']);
    Route::post('site-inventories', [SiteInventoryController::class, 'store']);
    Route::get('site-inventories/{site_inventory}', [SiteInventoryController::class, 'show']);
    Route::put('site-inventories/{site_inventory}', [SiteInventoryController::class, 'update']);
    Route::patch('site-inventories/{site_inventory}', [SiteInventoryController::class, 'update']);
    Route::delete('site-inventories/{site_inventory}', [SiteInventoryController::class, 'destroy']);

    // Payroll Management
    Route::post('payrolls/generate', [PayrollController::class, 'generate'])
        ->name('payrolls.generate');
    Route::post('payrolls/{payroll}/mark-as-paid', [PayrollController::class, 'markAsPaid'])
        ->name('payrolls.mark-as-paid');
    Route::get('payrolls', [PayrollController::class, 'index']);
    Route::post('payrolls', [PayrollController::class, 'store']);
    Route::get('payrolls/{payroll}', [PayrollController::class, 'show']);
    Route::put('payrolls/{payroll}', [PayrollController::class, 'update']);
    Route::patch('payrolls/{payroll}', [PayrollController::class, 'update']);
    Route::delete('payrolls/{payroll}', [PayrollController::class, 'destroy']);

    // Invoice Plans
    Route::get('invoice-plans', [InvoicePlanController::class, 'index']);
    Route::post('invoice-plans', [InvoicePlanController::class, 'store']);
    Route::get('invoice-plans/{invoice_plan}', [InvoicePlanController::class, 'show']);
    Route::put('invoice-plans/{invoice_plan}', [InvoicePlanController::class, 'update']);
    Route::patch('invoice-plans/{invoice_plan}', [InvoicePlanController::class, 'update']);
    Route::delete('invoice-plans/{invoice_plan}', [InvoicePlanController::class, 'destroy']);

    // Invoice Management
    Route::post('invoices/generate-from-plan', [InvoiceController::class, 'generateFromPlan'])
        ->name('invoices.generate-from-plan');
    Route::post('invoices/{invoice}/mark-as-paid', [InvoiceController::class, 'markAsPaid'])
        ->name('invoices.mark-as-paid');
    Route::get('invoices', [InvoiceController::class, 'index']);
    Route::post('invoices', [InvoiceController::class, 'store']);
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show']);
    Route::put('invoices/{invoice}', [InvoiceController::class, 'update']);
    Route::patch('invoices/{invoice}', [InvoiceController::class, 'update']);
    Route::delete('invoices/{invoice}', [InvoiceController::class, 'destroy']);

    // Transaction Management
    Route::get('transactions/summary', [TransactionController::class, 'summary'])
        ->name('transactions.summary');
    Route::get('transactions', [TransactionController::class, 'index']);
    Route::post('transactions', [TransactionController::class, 'store']);
    Route::get('transactions/{transaction}', [TransactionController::class, 'show']);
    Route::put('transactions/{transaction}', [TransactionController::class, 'update']);
    Route::patch('transactions/{transaction}', [TransactionController::class, 'update']);
    Route::delete('transactions/{transaction}', [TransactionController::class, 'destroy']);
});

// Fallback for invalid API endpoints
Route::fallback(function (Request $request) {
    return response()->json([
        'message' => 'Invalid API endpoint.',
        'status' => 404,
        'method' => $request->method(),
        'path' => $request->path(),
    ], 404);
});
