<?php


// ==================== CATEGORY CONTROLLER ====================
namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {   
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'Anda harus login untuk mengakses kategori');
        }

        // Authorize the user for category access
        // Check if the user is an admin
        if (Auth::user()->role === UserRole::Admin) {
            $categories = Category::withCount('books')->get();
            return view('admin.categories.index', compact('categories'));
        }

        // For regular users, only show categories with books
        $categories = Category::withCount('books')
            ->having('books_count', '>', 0)
            ->get();
        return view('categories.index', compact('categories'));
    }

    public function show(Category $category)
    {
        $books = $category->books()
            ->with('booksFile')
            ->paginate(12);
            
        return view('categories.show', compact('category', 'books'));
    }

    // Admin only methods
    public function create()
    {
        $this->authorize('admin-only');
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $this->authorize('admin-only');
        
        $validated = $request->validate([
            'category' => 'required|string|max:100|unique:categories',
        ]);

        Category::create([
            'category' => $validated['category'],
            'slug' => Str::slug($validated['category'])
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit(Category $category)
    {
        $this->authorize('admin-only');
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('admin-only');
        
        $validated = $request->validate([
            'category' => 'required|string|max:100|unique:categories,category,' . $category->id,
        ]);

        $category->update([
            'category' => $validated['category'],
            'slug' => Str::slug($validated['category'])
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy(Category $category)
    {
        $this->authorize('admin-only');
        
        if ($category->books()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus kategori yang masih memiliki buku');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}