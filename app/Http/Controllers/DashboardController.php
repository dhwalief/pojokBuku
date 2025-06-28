<?php

// ==================== DASHBOARD CONTROLLER ====================
namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === UserRole::Admin) {
            return $this->adminDashboard();
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
        return view('admin.dashboard.index', [
            'totalBooks' => Book::count(),
            'totalUsers' => User::where('role', 'user')->count(),
            'activeBorrows' => Borrow::where('status', 'Dipinjam')->count(),
            'totalCategories' => Category::count(),
            'booksThisMonth' => Book::whereMonth('created_at', now()->month)->count(),
            'activeUsers' => User::where('status', 'active')->count(),
            'todayBorrows' => Borrow::whereDate('created_at', today())->count(),
            'recentBorrows' => Borrow::with(['user', 'book'])->latest()->take(5)->get(),
            'recentBooks' => Book::latest()->take(5)->get(),
        ]);
    }

    private function userDashboard()
    {
        $user = Auth::user();

        $active_borrows = Borrow::with('book')
            ->where('user_id', $user->id)
            ->where('status', 'Dipinjam')
            ->get();

        $borrow_history = Borrow::with('book')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // $recommended_books = Book::with(['category'])
        //     ->whereNotIn('id', $user->borrows()->pluck('book_id'))
        //     ->inRandomOrder()
        //     ->take(6)
        //     ->get();

        return view('user.dashboard', compact('active_borrows', 'borrow_history'));
    }
}
