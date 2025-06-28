<?php

// ==================== BORROW CONTROLLER ====================
namespace App\Http\Controllers;

use App\Enums\BorrowStatus;
use App\Enums\Enums\UserStatus;
use App\Enums\UserRole;
use App\Models\Book;
use App\Models\Borrow;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BorrowController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        // $user = Auth::user();

        // if ($user->role === 'admin') {
        //     $borrows = Borrow::with(['user', 'book'])
        //         ->latest()
        //         ->paginate(15);
        //     return view('admin.borrows.index', compact('borrows'));
        // }

        // $borrows = Borrow::with('book')
        //     ->where('user_id', $user->id)
        //     ->latest()
        //     ->paginate(10);

        // return view('user.borrows.index', compact('borrows'));
        $borrows = Auth::user()->borrows
            ->with(['book.category'])
            ->when(request('search'), function ($query) {
                $query->whereHas('book', function ($q) {
                    $q->where('title', 'like', '%' . request('search') . '%')
                        ->orWhere('author', 'like', '%' . request('search') . '%');
                });
            })
            ->when(request('status'), function ($query) {
                $query->where('status', request('status'));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('user.borrows.index', compact('borrows'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Check user status
        if ($user->status !== UserStatus::Suspend) {
            return back()->with('error', 'Akun Anda sedang di-suspend');
        }

        // Check if user already has 3 active borrows
        $activeBorrows = $user->borrows
            ->where('status', 'dipinjam')
            ->count();

        if ($activeBorrows >= 3) {
            return back()->with('error', 'Anda sudah meminjam 3 buku. Kembalikan salah satu untuk meminjam lagi.');
        }

        $book = Book::findOrFail($request->book_id);

        // Check if user already borrowed this book
        $existingBorrow = $user->borrows
            ->where('book_id', $book->id)
            ->where('status', BorrowStatus::Borrowed)
            ->exists();

        if ($existingBorrow) {
            return back()->with('error', 'Anda sudah meminjam buku ini');
        }

        // Create borrow record (7 days loan period)
        Borrow::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'date_borrowed' => now(),
            'date_returned' => now()->addDays(7),
            'status' => BorrowStatus::Borrowed
        ]);

        return back()->with('success', 'Buku berhasil dipinjam. Batas pengembalian: ' . now()->addDays(7)->format('d M Y'));
    }

    public function return(Borrow $borrow)
    {
        $user = Auth::user();

        // Check ownership or admin
        if ($borrow->user_id !== $user->id && $user->role !== UserRole::Admin) {
            abort(403);
        }

        if ($borrow->status !== BorrowStatus::Borrowed) {
            return back()->with('error', 'Buku sudah dikembalikan');
        }

        $borrow->update([
            'status' => BorrowStatus::Returned,
            'date_returned' => now()
        ]);

        return back()->with('success', 'Buku berhasil dikembalikan');
    }

    // Admin only - extend borrow period
    public function extend(Request $request, Borrow $borrow)
    {
        $this->authorize('admin-only');

        $request->validate([
            'days' => 'required|integer|min:1|max:30'
        ]);

        if ($borrow->status !== BorrowStatus::Borrowed) {
            return back()->with('error', 'Hanya peminjaman aktif yang bisa diperpanjang');
        }

        $borrow->update([
            'date_returned' => Carbon::parse($borrow->date_returned)->addDays($request->days)
        ]);

        return back()->with('success', 'Masa peminjaman berhasil diperpanjang');
    }
}
