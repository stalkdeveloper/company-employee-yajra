<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('home');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');


Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::get('roles/list', [RoleController::class, 'index'])->name('role.list');

    //Route::resource('users', UserController::class);
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/list', [UserController::class, 'index'])->name('user.list');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/show/{id}', [UserController::class, 'show'])->name('users.show');
    Route::put('/users/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::get('/users/delete/{id}', [UserController::class, 'destroy'])->name('users.delete');

    route::get('/company', [CompanyController::class, 'index'])->name('company.index');
    route::get('/company/list', [CompanyController::class, 'getcompany'])->name('company.getcompany');
    route::get('/company/create', [CompanyController::class, 'create'])->name('company.create');
    route::post('/company/store', [CompanyController::class, 'store'])->name('company.store');
    route::get('/company/show/{id}', [CompanyController::class, 'show'])->name('company.show');
    route::put('/company/update/{id}', [CompanyController::class, 'update'])->name('company.update');
    route::get('/company/edit/{id}', [CompanyController::class, 'edit'])->name('company.edit');
    route::get('/company/delete/{id}', [CompanyController::class, 'destroy'])->name('company.delete');
    
    
    // Route::resource('employee', EmployeeController::class);
    route::get('/employee', [EmployeeController::class, 'index'])->name('employee.index');
    route::get('employee/list', [EmployeeController::class, 'index'])->name('employee.list');
    route::get('/employee/create', [EmployeeController::class, 'create'])->name('employee.create');
    route::post('/employee/store', [EmployeeController::class, 'store'])->name('employee.store');
    route::get('/employee/show/{id}', [EmployeeController::class, 'show'])->name('employee.show');
    route::put('/employee/update/{id}', [EmployeeController::class, 'update'])->name('employee.update');
    route::get('/employee/edit/{id}', [EmployeeController::class, 'edit'])->name('employee.edit');
    route::get('/employee/delete/{id}', [EmployeeController::class, 'destroy'])->name('employee.delete');

});
    
require __DIR__.'/auth.php';
