<?php

// File: app/Models/BooksFile.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BooksFile extends Model
{
    use HasFactory;

    protected $table = 'books_file';

    protected $fillable = [
        'file_path', 'file_hash', 'mime_type', 'file_size'
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'uploaded_at' => 'datetime',
        ];
    }

    // Relationships
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
