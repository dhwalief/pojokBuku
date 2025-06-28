<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('index');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::get('/dashboard', [userDashboard::class, 'dashboard'])->name('dashboard');
// });

require __DIR__ . '/auth.php';

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [BookController::class, 'search'])->name('books.search');
Route::get('/category/{slug}', [HomeController::class, 'booksByCategory'])->name('books.category');

// Books Routes (Public viewing)
Route::prefix('books')->group(function () {
    Route::get('/', [BookController::class, 'index'])->name('books.index');
    Route::get('/{book}', [BookController::class, 'show'])->name('books.show');
});

// Categories Routes (Public viewing)
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/{category}', [CategoryController::class, 'show'])->name('categories.show');
});

Route::get('/borrows', [BorrowController::class, 'index'])->name('borrows.index');
Route::patch('/borrows/{borrow}/return', [BorrowController::class, 'return'])->name('borrows.return');

// Authentication Routes (Laravel Breeze)
require __DIR__ . '/auth.php';
