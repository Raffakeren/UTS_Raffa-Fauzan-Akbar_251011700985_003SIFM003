<?php
use App\Http\Controllers\FurnitureController as F;
use Illuminate\Support\Facades\Route;

Route::get('/', [F::class, 'home'])->name('home');
Route::get('/produk', [F::class, 'products'])->name('produk');
Route::get('/produk/{id}', [F::class, 'show'])->name('produk.show');
Route::get('/kategori', [F::class, 'categoriesPage'])->name('kategori');
Route::get('/tentang-kami', [F::class, 'about'])->name('tentang');
Route::get('/checkout/{id}', [F::class, 'checkout'])->name('checkout');
Route::post('/checkout', [F::class, 'store'])->name('checkout.store');
Route::get('/pesanan/sukses', [F::class, 'success'])->name('sukses');