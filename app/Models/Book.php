<?php
// File: app/Models/Book.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'books_file_id', 'category_id', 'title', 'author', 
        'description', 'isbn', 'publisher', 'language', 
        'year_published', 'url_cover'
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
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
        return $query->whereDoesntHave('borrows', function($q) {
            $q->where('status', 'Dipinjam');
        });
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
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