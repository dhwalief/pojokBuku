<?php

// ==================== BOOK CONTROLLER ====================
namespace App\Http\Controllers;

use App\Enums\BorrowStatus;
use App\Enums\UserRole;
use App\Models\Book;
use App\Models\BooksFile;
use App\Models\Borrow;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Data untuk komponen statistik dan fitur di halaman utama
        $totalBooks = Book::count();
        $totalUsers = User::count();
        $totalBorrows = Borrow::count();

        // Mengambil kategori dengan jumlah buku terbanyak untuk ditampilkan
        $popularCategories = Category::withCount('books')->orderByDesc('books_count')->take(6)->get();

        // Mengambil buku-buku terbaru untuk bagian "Featured Books"
        $latestBooks = Book::with('category')->latest()->take(4)->get();

        // Mengambil SEMUA kategori untuk dropdown filter
        $categories = Category::orderBy('category')->get();

        // Query utama untuk daftar buku yang bisa dicari dan difilter
        $query = Book::with(['category', 'booksFile']);

        // Terapkan scopeSearch jika ada input pencarian
        if ($request->filled('search')) {
            $query->search($request->search); // Menggunakan scopeSearch dari Model
        }

        // Terapkan filter kategori jika ada
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Terapkan filter tahun jika ada
        if ($request->filled('year')) {
            $query->where('year_published', $request->year);
        }

        // Ambil data utama buku dengan paginasi
        $books = $query->latest()->paginate(12)->withQueryString();

        // Kembalikan view dengan semua data yang diperlukan
        return view('books.index', compact(
            'books',
            'popularCategories',
            'latestBooks',
            'totalBooks',
            'totalUsers',
            'totalBorrows',
            'categories' // Menambahkan variabel categories untuk filter
        ));
    }

    /**
     * Handle the search request from various forms and redirect to the index page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function search(Request $request)
    {
        // Validasi input pencarian, pastikan tidak kosong.
        $request->validate([
            'search' => 'required|string|max:100',
        ]);

        // Ambil hanya input 'search' dari request.
        $query = $request->only('search');

        // Redirect ke route 'books.index' dengan query pencarian.
        // Method index() sudah dirancang untuk menangani filter berdasarkan
        // parameter 'search', sehingga ini adalah cara yang paling efisien.
        return redirect()->route('books.index', $query);
    }

    public function show(Book $book)
    {
        $book->load(['category', 'booksFile']);

        // Cek apakah user sudah meminjam buku ini
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            // FIX: Hapus () setelah $user karena $user adalah objek, bukan method
            $user_borrowed = $user->borrows()
                ->where('book_id', $book->id)
                ->where('status', BorrowStatus::Borrowed)
                ->exists();
        } else {
            $user_borrowed = false; // penggguna belum login atau belum meminjam buku ini
        }

        return view('books.show', compact('book', 'user_borrowed'));
    }

    // ! =====   Admin only methods  ======

    public function create()
    {
        $this->authorize('admin-only');
        $categories = Category::all();
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('admin-only');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'isbn' => 'nullable|string|max:20|unique:books',
            'publisher' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:100',
            'year_published' => 'nullable|integer|min:1000|max:' . date('Y'),
            'url_cover' => 'nullable|url|max:255',
            'book_file' => 'required|file|mimes:pdf|max:10240', // 10MB max
        ]);

        // Handle file upload
        $file = $request->file('book_file');
        $fileName = Str::uuid() . '.pdf';
        $filePath = $file->storeAs('books', $fileName, 'private');
        $fileHash = hash_file('sha256', $file->getRealPath());

        // Create books_file record
        // FIX: Gunakan uploaded_at sesuai dengan ERD, bukan created_at/updated_at
        $booksFile = BooksFile::create([
            'file_path' => $filePath,
            'file_hash' => $fileHash,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_at' => now(), // Sesuai ERD
        ]);

        // Create book record
        Book::create([
            'books_file_id' => $booksFile->id,
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'author' => $validated['author'],
            'description' => $validated['description'],
            'isbn' => $validated['isbn'],
            'publisher' => $validated['publisher'],
            'language' => $validated['language'],
            'year_published' => $validated['year_published'],
            'url_cover' => $validated['url_cover'],
        ]);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil ditambahkan');
    }

    public function edit(Book $book)
    {
        $this->authorize('admin-only');
        $categories = Category::all();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $this->authorize('admin-only');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'isbn' => 'nullable|string|max:20|unique:books,isbn,' . $book->id,
            'publisher' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:100',
            'year_published' => 'nullable|integer|min:1000|max:' . date('Y'),
            'url_cover' => 'nullable|url|max:255',
        ]);

        $book->update($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil diupdate');
    }

    public function destroy(Book $book)
    {
        $this->authorize('admin-only');

        // Check if book is currently borrowed
        if ($book->borrows()->where('status', BorrowStatus::Borrowed)->exists()) {
            return back()->with('error', 'Tidak dapat menghapus buku yang sedang dipinjam');
        }

        // Delete file
        if ($book->booksFile) {
            Storage::disk('private')->delete($book->booksFile->file_path);
            $book->booksFile->delete();
        }

        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil dihapus');
    }

    // Secure PDF viewer - user cannot download
    public function viewPdf(Book $book)
    {
        // Check if user has active borrow for this book
        if (!Auth::check()) {
            abort(403, 'Anda harus login terlebih dahulu');
        }

        // Check if user has borrowed the book
        $user = Auth::user();
        $hasBorrowedBook = $user->borrows
            ->where('book_id', $book->id)
            ->where('status', BorrowStatus::Borrowed)
            ->exists();

        if (!$hasBorrowedBook && $user->role !== UserRole::Admin) {
            abort(403, 'Anda harus meminjam buku ini terlebih dahulu');
        }

        return view('books.pdf-viewer', compact('book'));
    }

    // Stream PDF content (prevent direct download)
    public function streamPdf(Book $book)
    {
        // Check if user is authenticated and has borrowed the book
        if (!Auth::check()) {
            abort(403);
        }

        $user = Auth::user();
        $hasBorrowedBook = $user->borrows
            ->where('book_id', $book->id)
            ->where('status', BorrowStatus::Borrowed)
            ->exists();

        if (!$hasBorrowedBook && $user->role !== UserRole::Admin) {
            abort(403);
        }

        $filePath = $book->booksFile->file_path;

        if (!Storage::disk('private')->exists($filePath)) {
            abort(404);
        }

        return response()->file(
            Storage::disk('private')->path($filePath),
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $book->title . '.pdf"',
                'X-Frame-Options' => 'SAMEORIGIN',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]
        );
    }
}
