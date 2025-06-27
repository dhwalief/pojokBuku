<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BooksFile extends Model
{
    protected $table = 'books_file';

    protected $fillable = [
        'file_path',
        'file_hash',
        'mime_type',
        'file_size',
    ];

    public function books()
    {
        return $this->hasMany(Book::class, 'books_file_id');
    }
}
