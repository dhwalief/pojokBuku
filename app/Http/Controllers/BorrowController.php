<?php

namespace App\Http\Controllers;

use App\Enums\BorrowStatus;
use App\Enums\UserStatus;
use App\Enums\UserRole;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\User;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BorrowController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = Auth::user();

        // Menggunakan Query Builder untuk efisiensi
        $borrowsQuery = Borrow::query();

        // Jika user adalah admin, tampilkan semua data peminjaman
        // Jika bukan, hanya tampilkan data milik user yang sedang login
        if ($user->role === UserRole::Admin) {
            $borrowsQuery->with(['user', 'book']); // Eager load relasi user dan book
        } else {
            $borrowsQuery->where('user_id', $user->id)->with('book'); // Hanya load relasi book
        }

        // Terapkan filter pencarian jika ada
        $borrowsQuery->when(request('search'), function ($query) {
            $query->whereHas('book', function ($q) {
                $q->where('title', 'like', '%' . request('search') . '%')
                    ->orWhere('author', 'like', '%' . request('search') . '%');
            });
        });

        // Terapkan filter status jika ada
        $borrowsQuery->when(request('status'), function ($query) {
            $query->where('status', request('status'));
        });

        // Urutkan berdasarkan data terbaru dan lakukan paginasi
        $borrows = $borrowsQuery->latest()->paginate(10);
        
        // Tentukan view berdasarkan role user
        $view = $user->role === UserRole::Admin ? 'admin.borrows.index' : 'user.borrows.index';

        return view($view, compact('borrows'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Validasi input book_id
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        // Check user status
        if ($user->status == UserStatus::Suspend) {
            return back()->with('error', 'Akun Anda sedang di-suspend dan tidak bisa meminjam buku.');
        }

        // Gunakan query langsung untuk menghitung peminjaman aktif
        $activeBorrowsCount = Borrow::where('user_id', $user->id)
            ->where('status', BorrowStatus::Borrowed)
            ->count();

        if ($activeBorrowsCount >= 3) {
            return back()->with('error', 'Anda sudah mencapai batas maksimal 3 buku yang dipinjam. Kembalikan salah satu untuk meminjam lagi.');
        }

        $book = Book::findOrFail($request->book_id);

        // Gunakan query langsung untuk cek peminjaman yang sudah ada
        $existingBorrow = Borrow::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->where('status', BorrowStatus::Borrowed)
            ->exists();

        if ($existingBorrow) {
            return back()->with('error', 'Anda sudah meminjam buku ini dan belum dikembalikan.');
        }

        // Sesuaikan dengan struktur model baru: due_date dan returned_at
        $dateBorrowed = now();
        $dueDate = now()->addDays(7); // Batas waktu pengembalian

        // Create borrow record dengan field yang sesuai model
        Borrow::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'date_borrowed' => $dateBorrowed,
            'due_date' => $dueDate, // Menggunakan due_date bukan date_returned
            'returned_at' => null, // Belum dikembalikan
            'status' => BorrowStatus::Borrowed
        ]);

        return back()->with('success', 'Buku berhasil dipinjam. Batas pengembalian: ' . $dueDate->format('d M Y'));
    }

    public function return(Borrow $borrow)
    {
        $this->authorize('update', $borrow); // Gunakan Policy untuk otorisasi

        if ($borrow->status !== BorrowStatus::Borrowed) {
            return back()->with('error', 'Buku ini sudah dalam status dikembalikan.');
        }

        // Update dengan struktur model baru
        $borrow->update([
            'status' => BorrowStatus::Returned,
            'returned_at' => now() // Set tanggal kembali aktual
        ]);

        return back()->with('success', 'Buku berhasil dikembalikan. Terima kasih!');
    }

    public function extend(Request $request, Borrow $borrow)
    {
        $this->authorize('admin-only'); // Pastikan hanya admin

        $request->validate([
            'days' => 'required|integer|min:1|max:30',
        ]);

        if ($borrow->status !== BorrowStatus::Borrowed) {
            return back()->with('error', 'Hanya peminjaman yang sedang aktif yang bisa diperpanjang.');
        }

        // Gunakan due_date sesuai dengan struktur model baru
        $newDueDate = $borrow->due_date->addDays($request->days);

        $borrow->update([
            'due_date' => $newDueDate, // Update due_date bukan date_returned
        ]);

        return back()->with('success', 'Masa peminjaman berhasil diperpanjang hingga ' . $newDueDate->format('d M Y'));
    }

    public function exportPdf()
    {
        // Pastikan hanya admin yang bisa mengakses fungsi ini
        $this->authorize('admin-only'); 

        // Ambil semua data peminjaman tanpa paginasi untuk laporan
        $borrows = Borrow::with(['user', 'book'])->latest()->get();

        // Siapkan data yang akan dikirim ke view PDF
        $data = [
            'title' => 'Laporan Data Peminjaman Buku',
            'date' => date('d/m/Y'),
            'borrows' => $borrows
        ];

        // Muat view PDF dan kirimkan datanya
        $pdf = app('dompdf.wrapper')->loadView('admin.borrows.pdf', $data);

        // Unduh file PDF dengan nama file yang spesifik
        return $pdf->download('laporan-peminjaman-' . date('Ymd') . '.pdf');
    }
}