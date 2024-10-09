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
use App\Http\Controllers\ReporteController;

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

Route::get('/', [homeController::class, 'index'])->name('panel');

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
    'profile' => profileController::class,
]);

// Rutas para reportes
Route::prefix('reportes')->group(function () {
    Route::get('ventas', [ReporteController::class, 'indexVentas'])->name('reportes.ventas.index');
    Route::get('reportes/ventas/totales', [ReporteController::class, 'ventasTotales'])->name('reportes.ventas.totales');

    Route::get('ventas/producto', [ReporteController::class, 'ventasPorProducto'])->name('reportes.ventas.producto');
    Route::get('ventas/cliente', [ReporteController::class, 'ventasPorCliente'])->name('reportes.ventas.cliente');
    Route::get('ventas/usuario', [ReporteController::class, 'ventasPorUsuario'])->name('reportes.ventas.usuario');

    Route::get('productos', [ReporteController::class, 'indexProductos'])->name('reportes.productos.index');
    Route::get('productos/inventario', [ReporteController::class, 'inventarioActual'])->name('reportes.productos.inventario');
    Route::get('productos/mas-vendidos', [ReporteController::class, 'productosMasVendidos'])->name('reportes.productos.mas_vendidos');
    Route::get('productos/bajo-stock', [ReporteController::class, 'bajoStock'])->name('reportes.productos.bajo_stock');
    Route::get('productos/historial/{id}', [ReporteController::class, 'historialVentas'])->name('reportes.productos.historial');
});


    Route::get('/ajustar-stock', [ProductoController::class, 'mostrarAjusteStock'])->name('productos.mostrarAjusteStock');
    Route::post('/ajustar-stock', [ProductoController::class, 'ajustarStock'])->name('productos.ajustarStock');




// Rutas de autenticación
Route::get('/login', [loginController::class, 'index'])->name('login');
Route::post('/login', [loginController::class, 'login']);
Route::get('/logout', [logoutController::class, 'logout'])->name('logout');

// Vistas de error
Route::get('/401', function () {
    return view('pages.401');
});
Route::get('/404', function () {
    return view('pages.404');
});
Route::get('/500', function () {
    return view('pages.500');
});




