<?php

use App\Http\Controllers\categoriaController;
use App\Http\Controllers\clienteController;
use App\Http\Controllers\compraController;
use App\Http\Controllers\PresentacioneController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\proveedoreController;
use App\Http\Controllers\ventaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\homeController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\logoutController;
use App\Http\Controllers\userController;
use App\Http\Controllers\roleController;
use App\Http\Controllers\profileController;
use App\Http\Controllers\registrosanitarioController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/',[homeController::class, 'index'  ])->name('panel');

Route::resources([
    'categorias' => categoriaController::class,
    'presentaciones' => PresentacioneController::class,
    'registrosanitarios' => registrosanitarioController::class,
    'productos' => ProductoController::class,
    'clientes' => clienteController::class,
    'proveedores' => proveedoreController::class,
    'compras' => compraController::class,
    'ventas' => ventaController::class,
    'users' => userController::class,
    'roles' => roleController::class,
    'profile' => profileController::class
]);



Route::get('/login',[loginController::class, 'index'  ])->name('login');
Route::post('/login',[loginController::class, 'login'  ]);
Route::get('/logout',[logoutController::class, 'logout'  ])->name('logout');
Route::get('/401', function () {
    return view('pages.401');
});

Route::get('/404', function () {
    return view('pages.404');
});

Route::get('/500', function () {
    return view('pages.500');
});
