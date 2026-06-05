<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

Route::get('/', [ProductController::class, 'index'])->name('shop.index');

// Rutas de Carrito
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/toggle', [CartController::class, 'toggle'])->name('cart.toggle');

// Rutas de Formulario
Route::post('/contact', [ProductController::class, 'contact'])->name('contact.submit');
Route::post('/sell-watch', [ProductController::class, 'sellWatch'])->name('sell.submit');

// Rutas de Administración
Route::post('/admin/login', [ProductController::class, 'login'])->name('admin.login');
Route::post('/admin/logout', [ProductController::class, 'logout'])->name('admin.logout');
Route::post('/admin/products', [ProductController::class, 'store'])->name('admin.products.store');
