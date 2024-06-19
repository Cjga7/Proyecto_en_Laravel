<?php

use App\Http\Controllers\categoriaController;
use App\Http\Controllers\clienteController;
use App\Http\Controllers\compraController;
use App\Http\Controllers\marcaController;
use App\Http\Controllers\PresentacioneController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\proveedoreController;
use App\Http\Controllers\ventaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\homeController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\logoutController;

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
    'marcas' => marcaController::class,
    'productos' => ProductoController::class,
    'clientes' => clienteController::class,
    'proveedores' => proveedoreController::class,
    'compras' => compraController::class,
    'ventas' => ventaController::class
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
