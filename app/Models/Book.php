<?php
// File: app/Models/Book.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'books_file_id',
        'title',
        'author',
        'description',
        'isbn',
        'publisher',
        'language',
        'year_published',
        'url_cover',
        'is_hidden'
    ];

    /**
     * Perubahan: Relasi diubah dari belongsTo menjadi belongsToMany.
     * Nama method diubah dari category() menjadi categories() untuk merefleksikan
     * bahwa satu buku bisa memiliki BANYAK kategori.
     */
    public function categories()
    {
        // Laravel akan mencari tabel pivot 'book_category' secara otomatis
        return $this->belongsToMany(Category::class, 'book_category');
    }

    public function booksFile()
    {
        return $this->belongsTo(BooksFile::class);
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->whereDoesntHave('borrows', function ($q) {
            $q->where('status', 'Dipinjam');
        });
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->whereHas('categories', function ($q) use ($categoryId) {
            $q->where('id', $categoryId);
        });
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'LIKE', "%{$search}%")
                ->orWhere('author', 'LIKE', "%{$search}%")
                ->orWhere('isbn', 'LIKE', "%{$search}%");
        });
    }

    // Helper methods
    public function getCoverUrlAttribute()
    {
        return $this->url_cover ?: asset('images/default-cover.png');
    }

    public function isAvailable()
    {
        return $this->borrows()->where('status', 'dipinjam')->count() === 0;
    }

    public function getFormattedYearPublishedAttribute()
    {
        return $this->year_published ? date('Y', strtotime($this->year_published)) : 'N/A';
    }
}
