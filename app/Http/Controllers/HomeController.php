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
     * Show the application homepage.
     */
    public function index()
    {
        // Get statistics
        $totalBooks = Book::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalBorrows = Borrow::count();

        // Get categories with book count
        $categories = Category::withCount('books')
            ->orderBy('books_count', 'desc')
            ->take(6)
            ->get();

        // Get latest books (limit 8)
        $latestBooks = Book::with(['category', 'booksFile'])
            ->orderBy('created_at', 'desc')
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
     * Search books
     */
    public function search(Request $request)
    {
        $search = $request->get('search');
        $categoryId = $request->get('category_id');
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $query = Book::with(['category', 'booksFile']);

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhere('publisher', 'like', "%{$search}%");
            });
        }

        // Apply category filter
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Apply sorting
        $query->orderBy($sortBy, $sortOrder);

        $books = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('books.search', compact('books', 'categories', 'search', 'categoryId', 'sortBy', 'sortOrder'));
    }

    /**
     * Show books by category
     */
    public function booksByCategory($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $books = Book::with(['category', 'booksFile'])
            ->where('category_id', $category->id)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('books.category', compact('books', 'category'));
    }
}
