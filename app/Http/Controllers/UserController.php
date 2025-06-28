<?php

// ==================== USER CONTROLLER ====================
namespace App\Http\Controllers\Admin;

use App\Enums\BorrowStatus;
use App\Enums\Enums\UserStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = User::where('role', 'user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->withCount(['borrows' => function($q) {
            $q->where('status', BorrowStatus::Borrowed);
        }])->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['borrows' => function($q) {
            $q->with('book')->latest();
        }]);

        return view('admin.users.show', compact('user'));
    }

    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:active,suspend'
        ]);

        $user->update(['status' => $request->status]);

        $message = $request->status === UserStatus::Suspend 
            ? 'User berhasil di-suspend' 
            : 'User berhasil diaktifkan';

        return back()->with('success', $message);
    }

    public function destroy(User $user)
    {
        // Check if user has active borrows
        if ($user->borrows()->where('status', BorrowStatus::Borrowed)->exists()) {
            return back()->with('error', 'Tidak dapat menghapus user yang memiliki peminjaman aktif');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }
}