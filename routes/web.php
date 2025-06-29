<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
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

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('books', BookController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('borrows', BorrowController::class)->except(['show']);
    Route::get('borrows/{borrow}/edit', [BorrowController::class, 'edit'])->name('borrows.edit');
    Route::patch('borrows/{borrow}/update', [BorrowController::class, 'update'])->name('borrows.update');
    
    // User Management Routes
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show'); // Route yang ditambahkan
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('users/{user}/status', [UserController::class, 'updateStatus'])->name('users.updateStatus'); // Tambahkan juga route ini jika belum ada
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});
