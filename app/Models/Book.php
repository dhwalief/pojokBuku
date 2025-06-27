<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'books_file_id',
        'category_id',
        'title',
        'author',
        'description',
        'isbn',
        'publisher',
        'language',
        'year_published',
        'url_cover'
    ];

    public function booksFile()
    {
        return $this->belongsTo(BooksFile::class, 'books_file_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
