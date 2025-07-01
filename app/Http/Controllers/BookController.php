<?php

// ==================== BOOK CONTROLLER (FIXED) ====================
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
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    // ... (method index dan search tidak perlu diubah) ...
    public function index(Request $request)
    {
        // Data untuk komponen statistik dan fitur di halaman utama
        $totalBooks = Book::count();
        $totalUsers = User::count();
        $totalBorrows = Borrow::count();

        // Mengambil kategori dengan jumlah buku terbanyak untuk ditampilkan
        $popularCategories = Category::withCount('books')->orderByDesc('books_count')->take(6)->get();

        // Mengambil buku-buku terbaru untuk bagian "Featured Books"
        $latestBooks = Book::with('categories')->latest()->take(4)->get();

        // Mengambil SEMUA kategori untuk dropdown filter
        $categories = Category::orderBy('category')->get();

        // Query utama untuk daftar buku yang bisa dicari dan difilter
        $query = Book::with(['categories', 'booksFile']);

        // Terapkan scopeSearch jika ada input pencarian
        if ($request->filled('search')) {
            $query->search($request->search); // Menggunakan scopeSearch dari Model
        }

        // PERUBAHAN: Logika filter kategori diubah menggunakan whereHas untuk relasi Many-to-Many
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('id', $request->category);
            });
        }

        // Terapkan filter tahun jika ada
        if ($request->filled('year')) {
            $query->where('year_published', $request->year);
        }
        
        $user = Auth::user();
        if (Auth::guest() || (Auth::check() && $user->role !== UserRole::Admin)) {
            $query->where('is_hidden', false);
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

    public function search(Request $request)
    {
        $request->validate(['search' => 'required|string|max:100']);
        $query = $request->only('search');
        return redirect()->route('books.index', $query);
    }


    /**
     * =================================================================
     * METHOD YANG DIPERBAIKI ADA DI SINI
     * =================================================================
     */
    /**
     * Menampilkan detail satu buku.
     */
    public function show(Book $book)
    {   
        /** @var User $user */
        $user = Auth::user();
        
        // Jika buku disembunyikan dan user bukan admin, tampilkan 404 Not Found.
        if ($book->is_hidden && (Auth::guest() || $user->role !== UserRole::Admin)) {
            abort(404);
        }

        $book->load(['categories', 'booksFile']);
        
        // Inisialisasi variabel peminjaman aktif
        $activeBorrow = null;

        // Cek apakah user sudah login
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            $activeBorrow = $user->borrows()
                ->where('book_id', $book->id)
                ->where('status', BorrowStatus::Borrowed)
                ->first(); // Gunakan first() untuk mendapatkan model Borrow
        }

        // Mengambil buku terkait (logika ini sudah benar)
        $categoryIds = $book->categories->pluck('id');
        $relatedBooksQuery = Book::query();
        if ($categoryIds->isNotEmpty()) {
            $relatedBooksQuery->whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            });
        }
        $relatedBooks = $relatedBooksQuery->where('id', '!=', $book->id)
            ->inRandomOrder()
            ->take(5)
            ->get();

        return view('books.show', compact('book', 'activeBorrow', 'relatedBooks'));
    }

    // ... (method lainnya tidak perlu diubah) ...
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
            'category_ids' => 'required|array', // Harus berupa array
            'category_ids.*' => 'exists:categories,id', // Setiap item dalam array harus ada di tabel categories
            'description' => 'nullable|string',
            'isbn' => 'nullable|string|max:20|unique:books',
            'publisher' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:100',
            'year_published' => 'nullable|integer|min:1000|max:' . date('Y'),
            'url_cover' => 'nullable|url|max:255',
            'book_file' => 'required|file|mimes:pdf|max:10240', // 10MB max tidak bisa file !pdf
        ]);

        // Handle file upload
        $file = $request->file('book_file');
        $fileName = Str::uuid() . '.pdf';
        $filePath = $file->storeAs('books', $fileName, 'private');
        $fileHash = hash_file('sha256', $file->getRealPath());

        // Create books_file record
        $booksFile = BooksFile::create([
            'file_path' => $filePath,
            'file_hash' => $fileHash,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_at' => now(),
        ]);

        // Create book record
        $book = Book::create([
            'books_file_id' => $booksFile->id,
            'title' => $validated['title'],
            'author' => $validated['author'],
            'description' => $validated['description'],
            'isbn' => $validated['isbn'],
            'publisher' => $validated['publisher'],
            'language' => $validated['language'],
            'year_published' => $validated['year_published'],
            'url_cover' => $validated['url_cover'],
        ]);

        //  Gunakan attach() untuk menyimpan relasi many-to-many
        if (!empty($validated['category_ids'])) {
            $book->categories()->attach($validated['category_ids']);
        }

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil ditambahkan');
    }

    public function edit(Book $book)
    {
        $this->authorize('admin-only');
        $categories = Category::all();
        $book->load('categories');
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $this->authorize('admin-only');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'description' => 'nullable|string',
            'isbn' => 'nullable|string|max:20|unique:books,isbn,' . $book->id,
            'publisher' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:100',
            'year_published' => 'nullable|integer|min:1000|max:' . date('Y'),
            'url_cover' => 'nullable|url|max:255',
        ]);

        $bookData = $request->except(['_token', '_method', 'category_ids']);
        $book->update($bookData);

        // PERUBAHAN: Gunakan sync() untuk update relasi. Sync akan otomatis menghapus relasi lama dan menambah yg baru.
        if (!empty($validated['category_ids'])) {
            $book->categories()->sync($validated['category_ids']);
        } else {
            $book->categories()->detach(); // Hapus semua kategori jika tidak ada yang dipilih
        }

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil diupdate');
    }

    public function destroy(Book $book)
    {
        $this->authorize('admin-only');

        if ($book->borrows()->where('status', BorrowStatus::Borrowed)->exists()) {
            return back()->with('error', 'Tidak dapat menghapus buku yang sedang dipinjam');
        }

        if ($book->booksFile) {
            Storage::disk('private')->delete($book->booksFile->file_path);
            $book->booksFile->delete();
        }

        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil dihapus');
    }

    /**
     * Mengubah status visibilitas buku (hide/unhide) (khusus admin).
     */
    public function toggleVisibility(Book $book)
    {
        $this->authorize('admin-only');

        $book->is_hidden = !$book->is_hidden;
        $book->save();

        $message = $book->is_hidden ? 'Buku berhasil disembunyikan.' : 'Buku berhasil ditampilkan kembali.';

        return redirect()->back()->with('success', $message);
    }

    public function viewPdf(Book $book)
    {
        if (!Auth::check()) {
            abort(403, 'Anda harus login terlebih dahulu');
        }
        /** @var User $user */
        $user = Auth::user();
        $hasBorrowedBook = $user->borrows()
            ->where('book_id', $book->id)
            ->where('status', BorrowStatus::Borrowed)
            ->exists();

        if (!$hasBorrowedBook && $user->role !== UserRole::Admin) {
            abort(403, 'Anda harus meminjam buku ini terlebih dahulu');
        }

        return view('books.pdf-viewer', compact('book'));
    }

    public function streamPdf(Book $book)
    {
        if (!Auth::check()) {
            abort(403);
        }

        /** @var User $user */
        $user = Auth::user();
        $hasBorrowedBook = $user->borrows()
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

        // Dapatkan ukuran file
        $fileSize = Storage::disk('private')->size($filePath);
        $fullPath = Storage::disk('private')->path($filePath);

        // Headers untuk PDF streaming yang aman
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Length' => $fileSize,
            'Accept-Ranges' => 'bytes', // Mendukung partial content untuk PDF.js

            // Security headers
            'X-Frame-Options' => 'SAMEORIGIN',
            'X-Content-Type-Options' => 'nosniff',
            'X-Robots-Tag' => 'noindex, nofollow, noarchive, nosnippet, notranslate',

            // Strict caching policy
            'Cache-Control' => 'private, no-cache, no-store, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => 'Thu, 01 Jan 1970 00:00:00 GMT',

            // CSP untuk PDF.js
            'Content-Security-Policy' => implode('; ', [
                "default-src 'self'",
                "script-src 'self' https://cdnjs.cloudflare.com 'unsafe-eval'",
                "style-src 'self' 'unsafe-inline'",
                "img-src 'self' data: blob:",
                "font-src 'self' data:",
                "connect-src 'self'",
                "object-src 'none'", // Blokir object tag
                "frame-src 'none'",
                "form-action 'none'",
                "base-uri 'self'"
            ]),

            // Additional security
            'Referrer-Policy' => 'no-referrer',
            'X-Download-Options' => 'noopen',
            'X-Permitted-Cross-Domain-Policies' => 'none',

            // Tracking headers
            'X-Book-ID' => $book->id,
            'X-User-ID' => $user->id,
            'X-Access-Time' => now()->toISOString(),
        ];

        // Handle range requests untuk PDF.js
        $rangeHeader = request()->header('Range');
        if ($rangeHeader) {
            return $this->handleRangeRequest($fullPath, $fileSize, $rangeHeader, $headers);
        }

        // Log akses
        Log::info('PDF accessed', [
            'book_id' => $book->id,
            'book_title' => $book->title,
            'user_id' => $user->id,
            'user_email' => $user->email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now(),
        ]);

        // Stream full file
        return response()->stream(function () use ($fullPath) {
            $stream = fopen($fullPath, 'rb');
            while (!feof($stream)) {
                echo fread($stream, 8192);
                flush();
            }
            fclose($stream);
        }, 200, $headers);
    }

    /**
     * Handle range requests untuk PDF.js streaming
     */
    private function handleRangeRequest($filePath, $fileSize, $rangeHeader, $baseHeaders)
    {
        // Parse range header
        if (!preg_match('/bytes=(\d+)-(\d*)/', $rangeHeader, $matches)) {
            abort(416, 'Invalid range');
        }

        $start = intval($matches[1]);
        $end = $matches[2] ? intval($matches[2]) : $fileSize - 1;

        // Validate range
        if ($start > $end || $start >= $fileSize || $end >= $fileSize) {
            abort(416, 'Invalid range');
        }

        $length = $end - $start + 1;

        // Range headers
        $headers = array_merge($baseHeaders, [
            'Content-Length' => $length,
            'Content-Range' => "bytes $start-$end/$fileSize",
        ]);

        return response()->stream(function () use ($filePath, $start, $length) {
            $stream = fopen($filePath, 'rb');
            fseek($stream, $start);

            $remaining = $length;
            while ($remaining > 0 && !feof($stream)) {
                $chunkSize = min(8192, $remaining);
                echo fread($stream, $chunkSize);
                $remaining -= $chunkSize;
                flush();
            }
            fclose($stream);
        }, 206, $headers); // 206 Partial Content
    }
}
