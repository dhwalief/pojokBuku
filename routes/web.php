<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [BookController::class, 'search'])->name('books.search');
Route::get('/category/{slug}', [HomeController::class, 'booksByCategory'])->name('books.category');

// Books Routes (Public viewing)
Route::prefix('books')->name('books.')->group(function () {
    Route::get('/', [BookController::class, 'index'])->name('index');
    Route::get('/{book}', [BookController::class, 'show'])->name('show');
});

// Categories Routes (Public viewing)
Route::prefix('categories')->name('categories.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
});


// User-specific routes that require authentication
Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Borrowing Routes
    Route::prefix('borrows')->name('borrows.')->group(function () {
        Route::get('/', [BorrowController::class, 'index'])->name('index');
        Route::post('/', [BorrowController::class, 'store'])->name('store');
        Route::patch('/{borrow}/return', [BorrowController::class, 'return'])->name('return');
    });


    // Route untuk menampilkan halaman/template viewer PDF
    Route::get('/books/{book}/pdf/viewer', [BookController::class, 'viewPdf'])->name('books.pdf.viewer')->middleware(['auth', 'pdf.security']);

    // Route untuk streaming konten file PDF ke dalam viewer (misal: untuk src iframe)
    Route::get('/books/{book}/pdf/stream', [BookController::class, 'streamPdf'])->name('books.pdf.stream')->middleware(['auth', 'pdf.security']);
});


// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Resource routes for admin management
    Route::resource('books', BookController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);

    Route::patch('books/{book}/toggle-visibility', [BookController::class, 'toggleVisibility'])->name('books.toggleVisibility');

    // Admin specific borrow management
    // (Asumsi ada method adminIndex di BorrowController, jika tidak ada, sesuaikan)
    Route::get('borrows', [BorrowController::class, 'adminIndex'])->name('borrows.index');
    Route::get('borrows/{borrow}/edit', [BorrowController::class, 'edit'])->name('borrows.edit');
    Route::patch('borrows/{borrow}', [BorrowController::class, 'update'])->name('borrows.update');

    // User Management Routes
    Route::resource('users', UserController::class)->except(['create', 'store']);
    Route::patch('users/{user}/status', [UserController::class, 'updateStatus'])->name('users.updateStatus');

    // Export Routes
    Route::get('borrows/export/pdf', [BorrowController::class, 'exportPdf'])->name('borrows.export.pdf');
});

// Authentication Routes (Laravel Breeze)
require __DIR__ . '/auth.php';
