<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use App\Models\Borrow;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama aplikasi.
     */
    public function index()
    {
        // Mengambil data statistik
        $totalBooks = Book::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalBorrows = Borrow::count();

        // Mengambil kategori beserta jumlah bukunya
        $categories = Category::withCount('books')
            ->orderBy('books_count', 'desc')
            ->take(6)
            ->get();

        // Mengambil buku-buku terbaru (limit 8)
        $latestBooks = Book::with(['categories', 'booksFile']) // <-- FIX
            ->latest() // Menggunakan latest() lebih ringkas dari orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return view('index', compact(
            'totalBooks',
            'totalUsers',
            'totalBorrows',
            'categories',
            'latestBooks'
        ));
    }

    /**
     * Menampilkan halaman hasil pencarian buku.
     * Method ini tidak lagi digunakan karena pencarian di-handle oleh BookController@index.
     * Namun, jika Anda masih ingin menggunakannya, ini adalah versi yang diperbaiki.
     */
    public function search(Request $request)
    {
        $search = $request->get('search');
        $categoryId = $request->get('category_id');
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $query = Book::with(['categories', 'booksFile']); // <-- FIX

        // Terapkan filter pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhere('publisher', 'like', "%{$search}%");
            });
        }

        // =================================================================
        // PERBAIKAN 3: Menggunakan whereHas untuk filter relasi Many-to-Many
        // =================================================================
        // Terapkan filter kategori
        if ($categoryId) {
            $query->whereHas('categories', function ($q) use ($categoryId) { // <-- FIX
                $q->where('categories.id', $categoryId);
            });
        }

        // Terapkan sorting
        $query->orderBy($sortBy, $sortOrder);

        $books = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('books.search', compact('books', 'categories', 'search', 'categoryId', 'sortBy', 'sortOrder'));
    }

    /**
     * Menampilkan buku berdasarkan kategori.
     */
    public function booksByCategory($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        // =================================================================
        // PERBAIKAN 4: Menggunakan whereHas dan memuat relasi 'categories'
        // =================================================================
        $books = Book::with(['categories', 'booksFile']) // <-- FIX
            ->whereHas('categories', function ($q) use ($category) { // <-- FIX
                $q->where('slug', $category->slug);
            })
            ->latest()
            ->paginate(12);

        return view('books.category', compact('books', 'category'));
    }
}
