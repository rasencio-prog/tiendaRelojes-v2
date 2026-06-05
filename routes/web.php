<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ProductoController as AdminProductoController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas de la Tienda
|--------------------------------------------------------------------------
*/

Route::get('/', [TiendaController::class, 'index'])->name('tienda.index');
Route::post('/contacto', [TiendaController::class, 'contacto'])->name('contacto.enviar');
Route::post('/vender-reloj', [TiendaController::class, 'venderReloj'])->name('vender.enviar');

/*
|--------------------------------------------------------------------------
| Rutas del Carrito de Compras (Sesión)
|--------------------------------------------------------------------------
*/

Route::post('/carrito/agregar/{id}', [CartController::class, 'agregar'])->name('carrito.agregar');
Route::post('/carrito/actualizar/{id}', [CartController::class, 'actualizar'])->name('carrito.actualizar');
Route::post('/carrito/eliminar/{id}', [CartController::class, 'eliminar'])->name('carrito.eliminar');
Route::post('/carrito/vaciar', [CartController::class, 'vaciar'])->name('carrito.vaciar');
Route::post('/carrito/toggle', [CartController::class, 'toggle'])->name('carrito.toggle');

/*
|--------------------------------------------------------------------------
| Rutas de Autenticación del Panel de Administración
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [LoginController::class, 'mostrarLogin'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Panel de Administración (protegido con middleware auth)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // CRUD de Productos
    Route::get('/productos', [AdminProductoController::class, 'index'])->name('productos.index');
    Route::get('/productos/crear', [AdminProductoController::class, 'create'])->name('productos.create');
    Route::post('/productos', [AdminProductoController::class, 'store'])->name('productos.store');
    Route::get('/productos/{producto}/editar', [AdminProductoController::class, 'edit'])->name('productos.edit');
    Route::put('/productos/{producto}', [AdminProductoController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{producto}', [AdminProductoController::class, 'destroy'])->name('productos.destroy');
    Route::patch('/productos/{producto}/toggle-activo', [AdminProductoController::class, 'toggleActivo'])->name('productos.toggle-activo');

});
