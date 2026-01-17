<?php

// ==================== DASHBOARD CONTROLLER ====================
namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Category;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === UserRole::Admin) {
            return $this->adminDashboard();
        }

        if ($user->role === UserRole::User) {
            return $this->userDashboard();
        }

        return $this->userDashboard();
    }

    private function adminDashboard()
    {
        // $stats = [
        //     'total_books' => Book::count(),
        //     'total_users' => \App\Models\User::where('role', 'user')->count(),
        //     'total_categories' => Category::count(),
        //     'active_borrows' => Borrow::where('status', 'dipinjam')->count(),
        //     'overdue_borrows' => Borrow::where('status', 'dipinjam')
        //         ->where('date_returned', '<', now())
        //         ->count()
        // ];

        // $recent_borrows = Borrow::with(['user', 'book'])
        //     ->latest()
        //     ->take(5)
        //     ->get();

        // return view('admin.dashboard', compact('stats', 'recent_borrows'));

        $month = date('m');

        return view('admin.dashboard.index', [
            'totalBooks' => Book::count(),
            'totalUsers' => User::where('role', 'user')->count(),
            'activeBorrows' => Borrow::where('status', 'Dipinjam')->count(),
            'totalCategories' => Category::count(),
            // debugging karena error parse carbon di windows (di Linux tidak error)
            'booksThisMonth' => Book::whereMonth('created_at', [$month])->count(),
            'activeUsers' => User::where('status', 'active')->count(),
            'todayBorrows' => Borrow::whereDate('created_at', today())->count(),
            'recentBorrows' => Borrow::with(['user', 'book'])->latest()->take(5)->get(),
            'recentBooks' => Book::latest()->take(5)->get(),
        ]);
    }

    private function userDashboard()
    {
        $user = Auth::user();

        // $active_borrows = Borrow::with('book')
        //     ->where('user_id', $user->id)
        //     ->where('status', 'Dipinjam')
        //     ->get();

        // $borrow_history = Borrow::with('book')
        //     ->where('user_id', $user->id)
        //     ->latest()
        //     ->take(5)
        //     ->get();

        // +++++++++++++++++++++++++++++

        // $recommended_books = Book::with(['category'])
        //     ->whereNotIn('id', $user->borrows()->pluck('book_id'))
        //     ->inRandomOrder()
        //     ->take(6)
        //     ->get();

        $totalBooksRead = Borrow::where('user_id', $user->id)->count();

        // Buku yang sedang dipinjam (status 'Dipinjam')
        $currentlyBorrowed = Borrow::where('user_id', $user->id)
            ->where('status', 'Dipinjam')
            ->count();

        // Total hari membaca (menghitung durasi semua peminjaman)
        // menggunakan native DateTime untuk menghitung selisih hari 
        $totalReadingDays = Borrow::where('user_id', $user->id)
            ->get()
            ->sum(function ($borrow) {
                try {
                    $borrowed = new DateTime($borrow->date_borrowed);
                    $returned = $borrow->date_returned
                        ? new DateTime($borrow->date_returned)
                        : new DateTime(); // default ke sekarang jika belum dikembalikan
                    return $borrowed->diff($returned)->days;
                } catch (Exception $e) {
                    return 0;
                }
            });

        // Buku yang sedang dipinjam dengan detail
        $currentBorrows = Borrow::with(['book', 'book.category'])
            ->where('user_id', $user->id)
            ->where('status', 'Dipinjam')
            ->orderBy('date_borrowed', 'desc')
            ->get();

        // Riwayat peminjaman terbaru (5 terakhir)
        $recentBorrows = Borrow::with(['book', 'book.category'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // return view('user.dashboard', compact('active_borrows', 'borrow_history'));

        return view('user.dashboard.index', compact(
            'totalBooksRead',
            'currentlyBorrowed',
            'totalReadingDays',
            'currentBorrows',
            'recentBorrows'
        ));
    }
}
